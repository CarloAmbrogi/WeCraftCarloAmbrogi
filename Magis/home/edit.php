<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// config:


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:
engine_auth(array("user", "manager"));


// request:
$cid = $_GET["c"];


// data:

if ($cid > 0) {
	$riga = $database->sel_record("Metadata", "MediaCode = $cid");
	if ($riga["CodePOI"] > 0) $poi = $database->sel_record("POI", "CodePOI = '".$riga["CodePOI"]."'");
	else $poi = array();
}
else {
	$riga = array();
	$poi = array();
}

$pois = $database->sel_data("POI");



// logic:
$poid_list = "[";
foreach ($pois as $apoi) { // lista di tutti i PoIs tranne quello eventualmente già associato al content-item
   // $poid_list .= $apoi["CodePOI"] . ",";
   if ($apoi["CodePOI"] != $riga["CodePOI"]) $poid_list .= $apoi["CodePOI"] . ",";
}
$poid_list .= "]";
/// replace ",]" with "]"



// view:
include "../include/adm_header.php";
include "../include/edit2.tpl.php";
include "../include/footer.php";
?>
