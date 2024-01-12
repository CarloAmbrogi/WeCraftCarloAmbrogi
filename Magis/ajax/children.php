<?php

// restituisce il


include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
/// engine_auth(array("user", "manager"));


// request:
$tagid = $_POST["tagId"];


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



// data:

$tag = $database->sel_record("Tags", "TagID = '$tagid'");
$tagName = $tag["Name"];
$tagVoce = $tag[$language];

$query = "SELECT $language, TagID, Name FROM Tags JOIN Ontology ON Tags.Name = Ontology.Tag1 WHERE Relation = 'is_a' and Tag2 = '$tagName' ORDER BY $language";
$voci = $database->select($query);



// logic:

$output = "";
$q = 0;

if ($voci) {
  foreach ($voci as $voce) { // per ogni nodo foglia << forse prima verificare se ha contenuti... si potrebbe lasciare in sessione uno stringone con tutti i nodi che hanno contenuti ?
                             // oppure più semplicemente fare qui una select count, prima di aggiungerlo

	$tid = $voce["TagID"];
	$label = "<span>" . $voce[$language] . "</span>";

	$item = "<li class='list-group-item topicitem' id='item_$tid' >";
	$item .= "<input class='form-check-input me-1' type='checkbox' id='chk_$tid' value='' aria-label='topic item'>";
	$item .= $label;
	$item .= "<a onclick=\"tagbranch('$tid')\" ><i class='fa fa-chevron-down float-end' aria-hidden='true'></i></a>";
	$item .= '</li>';

	$output .= $item;
       $q++;
  }
} 


$container .= "<input class='form-check-input me-1' type='checkbox' id='chk_$tagid' value='' disabled aria-label='topic item'>";
// $container .= "<span>$tagVoce</span><br><br>";
if ($q > 0) $container .= "<span>$tagVoce</span><br><br>";
else $container .= "<span>$tagVoce</span>";


$output = $container . $output;



/// $output = "ajax content...";




// view:

$resp = array();
$resp["errMsg"] = false;
//$resp["target"] = "#item_" . $tagid;
$resp["target"] = $tagid;
$resp["htm"] = $output;


// response:

echo json_encode($resp); 
?>