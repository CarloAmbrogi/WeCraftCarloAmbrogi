<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:
engine_auth(array("user", "manager"));


// data:

// $pois = array(); // se caricamento iniziale vuoto
// $pois = $database->sel_data("POI"); // se caricamento iniziale completo
//$pois = $database->sel_data("POI", "CodePOI < 10"); // caricamento parziale di test
$pois = $database->sel_data("POI", "true"); // caricamento totale di test



$res = $database->select("select MIN(PublicationDate) as thedate from Metadata where PublicationDate > '0000-00-00 00:00:00' ");
if ($res) {
  $nrows = $res->num_rows;
  $riga = $res->fetch_assoc();
  $mindate = $riga["thedate"];
}
$res = $database->select("select MAX(PublicationDate) as thedate from Metadata where PublicationDate > '0000-00-00 00:00:00' ");
if ($res) {
  $nrows = $res->num_rows;
  $riga = $res->fetch_assoc();
  $maxdate = $riga["thedate"];
}



// logic:

$poid_list = "[";
   foreach ($pois as $apoi) { // lista di tutti i PoIs 
      $poid_list .= $apoi["CodePOI"] . ",";
   }
$poid_list .= "]";
/// replace ",]" with "]"



$topictree = "(loading tags...)";



// view:
include "../include/adm_header.php";
include "../include/view_tpl.php";
include "../include/footer.php";
?>
