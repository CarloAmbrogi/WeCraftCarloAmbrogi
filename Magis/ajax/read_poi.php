<?php

// come la get_poi ma accessibile al pubblico


include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
/// engine_auth(array("user", "manager"));


// request:
$cid = $_POST["poiId"];


// data:
if ($cid > 0) $riga = $database->sel_record("POI", "CodePOI = '$cid'");
else $riga = array();

$resp = array();
$resp["id"] = $riga["CodePOI"];
$resp["lat"] = $riga["Latitude"];
$resp["lng"] = $riga["Longitude"];
$resp["name"] = $riga["Name"];


// response:
echo json_encode($resp); 
?>