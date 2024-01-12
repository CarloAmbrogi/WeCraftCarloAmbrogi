<?php

include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

/*
// accesso alla sessione:
include ("../engine/" . "sessions.inc.php");
sn_enable();
*/

// request:
$riga = $_POST;


// data:
/// $esito = $database->do_update ($riga, "Metadata", "MediaCode", true); // debug
$esito = $database->do_update ($riga, "Metadata", "MediaCode");


// view:
if ($esito) $messaggio = "Aggiornamento effettuato";
else $messaggio = "Errore in fase di update";
/// $messaggio = $esito; // debug

add_json ("message", $messaggio);
// add_script ("$('#txtLink').val('');");


// response:
echo json_encode($json_out); 
?>