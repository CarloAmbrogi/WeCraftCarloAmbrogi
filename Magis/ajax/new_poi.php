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
unset($riga["CodePOI"]); // che qui dovrebbe essere zero


// data:
/// $esito = $database->do_insert ($riga, "POI", "", true);
$esito = $database->do_insert ($riga, "POI", "");


// view:
///add_script ("alert('$esito');");
if ($esito) add_script ("updatepoi('$esito');");
else add_script ("alert('Errore in fase di inserimento');");


// response:
echo json_encode($json_out); 
?>