<?php

include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
engine_auth(array("user", "manager"));


// request:
$riga = $_POST;


// data:
$esito = $database->do_update ($riga, "POI", "CodePOI");


// view:
if ($esito) add_script ("alert('POI ".$riga["Name"]." updated');");
else add_script ("alert('Errore in fase di aggiornamento');");


// response:
echo json_encode($json_out); 
?>