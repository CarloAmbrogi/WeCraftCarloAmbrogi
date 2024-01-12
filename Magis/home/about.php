<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


/*
// session:
session_start();
$userinfo = $_SESSION["userinfo"];
*/


// data:


// logic:


// content:

$txt = "<h4>Multimedia Adaptive Geographic Information System</h4><br>";
$txt .= "This web space is made available by i3Lab to support the Magis project.<br>";
$txt .= "Please log in with your authorization credentials to access the full demo features.<br>";



$contenuto = $txt;





// view:
include "../include/header.php";
include "../include/page_tpl.php";
include "../include/footer.php";
?>
