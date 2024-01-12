<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";



// session:
session_start();
$userinfo = $_SESSION["userinfo"];



// data:


// logic:


// content:

$txt = "<h4>Under development</h4><br>";
$txt .= "This page is still in progress...<br>";



$contenuto = $txt;





// view:
include "../include/adm_header.php";
include "../include/page_tpl.php";
include "../include/footer.php";
?>
