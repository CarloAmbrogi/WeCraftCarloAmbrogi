<?php


include "../database/db_connect.inc.php";


echo $database->connected;


/*
$sql = "select * from test where valore > 10";
$righe = $database->select($sql);
foreach ($righe as $riga) { echo $riga["nome"].", "; } 
*/


/*
$cond = "valore = 15";
$riga = $database->sel_record("test", $cond);
if ($riga) echo $riga["nome"];
*/


/*
$cond = "valore > 10";
$righe = $database->sel_data("test", $cond);
foreach ($righe as $riga) { echo $riga["nome"].", "; } 
*/


/*
$riga = array();
$riga["nome"] = "pere";
$riga["valore"] = "8";
// $esito = $database->do_update ($riga, "test", "nome", true); 
$esito = $database->do_update ($riga, "test", "nome");
echo $esito;
echo "<br/>";
$riga = $database->sel_record("test","nome = 'pere'");
if ($riga) echo $riga["valore"];
*/


/*
$riga = array();
$riga["nome"] = "limoni";
$riga["valore"] = "11";
// $esito = $database->do_insert ($riga, "test", "", true);
$esito = $database->do_insert ($riga, "test", "");
echo $esito;
echo "<br/>";
$righe = $database->sel_data("test");
foreach ($righe as $riga) { echo $riga["nome"].", "; } 
*/


/*
// $esito = $database->do_remove ("limoni", "test", "nome", true);
$esito = $database->do_remove ("limoni", "test", "nome");
echo $esito;
echo "<br/>";
$righe = $database->sel_data("test");
foreach ($righe as $riga) { echo $riga["nome"].", "; } 
*/

?>