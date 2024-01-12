<?php

// config:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:



// libs:



// request:
// $riga = $_POST;



// data:
// $riga = $database->sel_record("settings", "name = 'passepartout'");
$riga = $database->sel_record("Users", "username = '".$_POST["usr"]."'");



// logic:

if ($riga["password"] != $_POST["pwd"]) $errore = "Warning: invalid password"; //  . ": ".$riga["password"] . "," . $_POST["pwd"] ;
else $errore = "";


if ($errore > "") {
	$message = $errore;
}
else {
	$userinfo["ruolo"] = $riga["type"];
	$userinfo["nick"] = $riga["username"];
	$_SESSION["userinfo"] = $userinfo;

	header("Location: list.php"); // rinvia alla logged-in homepage
	exit;
}


// view:
include "../include/adm_header.php";
include "../include/error_tpl.php";
include "../include/footer.php";

?>
