<?php

// restituisce i top nodes della rete is-a, formattati come elementi <li>
 


include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
/// engine_auth(array("user", "manager"));


// request:
$cid = $_POST["poiId"]; // qui non utlizzata, riceve sempre zero


// language setting:
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



// data:

$query = "SELECT Tags.Name, count(Tags.Name) as Quant from Tags JOIN MetadataTags ON Tags.TagID = MetadataTags.TagID group by Tags.Name";
$query .= " ORDER BY Name";
$righe = $database->select("$query");


// logic:

$output = "";

foreach ($righe as $riga) {  // per ogni nodo foglia (con contenuti)

	$nome = $riga["Name"];
	$quanti = $riga["Quant"];

	Risalire($nome);
}

// sort ($topnodes); // ordina alfabeticamente (inutile se poi da mappare su language)

$elenco = "(";
foreach ($topnodes as $nodo) {  // per ogni nodo top

	$elenco .= "'$nodo',";
}
$elenco .= ")";
$elenco = str_replace(",)", ")", $elenco);

/// $output = $elenco;

$condizionePerTraduzioniLinguistiche = "Name IN $elenco ORDER BY $language";
if($elenco == "()"){
	$condizionePerTraduzioniLinguistiche = "false";
}
$voci = $database->sel_data("Tags", $condizionePerTraduzioniLinguistiche); // traduzioni linguistiche

foreach ($voci as $voce) { 

	$tid = $voce['TagID'];
	$label = "<span>" . $voce[$language] . "</span>";

	$item = "<li class='list-group-item topicitem' id='item_$tid' >";
	$item .= "<input class='form-check-input me-1' type='checkbox' id='chk_$tid' value='' aria-label='topic item'>";
	$item .= $label;
	$item .= "<a onclick=\"tagbranch('$tid')\" ><i class='fa fa-chevron-down float-end' aria-hidden='true'></i></a>";
	$item .= '</li>';

	$output .= $item;
}

$output = '<ul class="list-group">' . $output . '</ul>';


// $output = "ajax content"; // debug




// view:

$resp = array();
$resp["errMsg"] = false;
$resp["htm"] = $output;
// $resp = $output; // se chiamata con loadElem


// response:

echo json_encode($resp); 
?>