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
/// $esito = $database->do_insert ($riga, "Metadata", "", true);
$esito = $database->do_insert ($riga, "Metadata", "");


// view:
if ($esito) $messaggio = "Inserimento effettuato";
else $messaggio = "Errore in fase di inserimento";
add_json ("contentForm", $messaggio);
add_script ("$('#txtLink').val('');");


// response:
echo json_encode($json_out); 
?>