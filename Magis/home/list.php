<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// config:



// session:
session_start();
$userinfo = $_SESSION["userinfo"];


// authorization:
engine_auth(array("user", "manager"));



// data:
// $cond = "valore > 10";
// $righe = $database->sel_data("metadata", $cond);
$righe = $database->sel_data("Metadata");



// logic:


// view:

include "../include/adm_header.php";
include "../include/list.tpl.php";
include "../include/footer.php";


?>
