<?php

// config:
include "../include/engine.inc.php";


// session:
session_start();


// logic:
$message = "Warning: access denied. To access this page please login.";


// view:
include "../include/adm_header.php";
include "../include/error_tpl.php";
include "../include/footer.php";
?>
