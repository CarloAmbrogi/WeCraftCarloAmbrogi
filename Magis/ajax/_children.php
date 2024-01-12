<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";



// config:
$language = "ExprIT";
//$language = "ExprUK";



// request
$nodo = $_REQUEST["n"];


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:
engine_auth(array("user", "manager"));



// data:

$query = "SELECT $language, Name FROM Tags JOIN Ontology ON Tags.Name = Ontology.Tag1 WHERE Relation = 'is_a' and Tag2 = '$nodo' ORDER BY $language";
$righe = $database->select($query);



// logic:

$out = "";

foreach ($righe as $riga) {  // per ogni nodo foglia (con contenuti)

	$nome = $riga["Name"];
	$voce = $riga[$language];

	$out .= $voce . " ($nome) <br>";
}

$out .= "<br><br>fine.";




// view:

$contenuto = $out;


include "../include/adm_header.php";
include "../include/page_tpl.php";
include "../include/footer.php";


?>
