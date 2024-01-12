<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// data:
$pois = $database->sel_data("POI");


// logic:
$poid_list = "[";
if ($userinfo["ruolo"]) {
   foreach ($pois as $apoi) { // lista di tutti i PoIs 
      $poid_list .= $apoi["CodePOI"] . ",";
   }
}
$poid_list .= "]";
/// replace ",]" with "]"



// view:
include "../include/header.php";
include "../include/home_tpl.php";
include "../include/footer.php";
?>
