<?php

// libs:
include "../include/engine.inc.php";



// config:



// session:
session_start();
$userinfo = $_SESSION["userinfo"];



// authorization:
engine_auth(array("user", "manager"));


// request:


// data:


// logic:


// view:

include "../include/adm_header.php";
include "../include/upload_tpl.php";
include "../include/footer.php";


?>
