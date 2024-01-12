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
$pois = $database->sel_data("POI");

/*
select("select from POI where ")
*/



// logic:
$poid_list = "[";
   foreach ($pois as $apoi) { // lista di tutti i PoIs 
      $poid_list .= $apoi["CodePOI"] . ",";
   }
$poid_list .= "]";
/// replace ",]" with "]"



// view:
include "../include/adm_header.php";
include "../include/home2_tpl.php";
include "../include/footer.php";
?>
