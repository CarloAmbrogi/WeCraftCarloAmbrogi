<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


$out = "";
$topnodes = array();

function Risalire ($nodo) {

	global $database;
	global $topnodes;

	$q = 0;

	$trovato = $database->sel_record("Ontology", "Tag1 = '$nodo' AND relation = 'is_a'");  // cercare se compare in Tag1
	if ($trovato) { 
		$father = $trovato["Tag2"];  // se compare, considero il tag2

		if (in_array($father, $topnodes)) $q++; // se è già nell'array top, mi fermo (dovrei sommare il numero)
		else Risalire($father);  // se non è già presente, itero ricorsivamente

	}
	else $topnodes[] = $nodo;  // se non compare, inserisco il nodo nell'array dei nodi top (con il numero)

} // end function



// config:
$language = "ExprIT";
//$language = "ExprUK";
//Load the language from WeCraft settings
$acceptLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($_COOKIE['language'])){
	$acceptLanguage = $_COOKIE['language'];
}
if($acceptLanguage != "it"){
	$acceptLanguage = "en";
}
if($acceptLanguage == "en"){
	$language = "ExprUK";
}


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:
engine_auth(array("user", "manager"));



// data:

$query = "SELECT Tags.Name, count(Tags.Name) as Quant from Tags JOIN MetadataTags ON Tags.TagID = MetadataTags.TagID group by Tags.Name";
$query .= " ORDER BY Name";

$righe = $database->select("$query");



// logic:

foreach ($righe as $riga) {  // per ogni nodo foglia (con contenuti)

	$nome = $riga["Name"];
	$quanti = $riga["Quant"];

	Risalire($nome);
}

/// $out .= print_r($topnodes, true);

// sort ($topnodes); // ordina alfabeticamente

$elenco = "(";
foreach ($topnodes as $nodo) {  // per ogni nodo top

	$elenco .= "'$nodo',";
}
$elenco .= ")";
$elenco = str_replace(",)", ")", $elenco);

/// $out = $elenco;

$voci = $database->sel_data("Tags", "Name IN $elenco ORDER BY $language"); // traduzioni linguistiche

foreach ($voci as $voce) { 

	/// $out .= $voce . " ($nome) <br>";
	$out .= $voce[$language] . " (" . $voce[Name] . ")" . "<br>";
}





$out .= "<br>/// fine.";




// view:

$contenuto = $out;


include "../include/adm_header.php";
include "../include/page_tpl.php";
include "../include/footer.php";


?>
