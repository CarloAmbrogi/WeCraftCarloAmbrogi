<?php

// libs:
include "../database/db_connect.inc.php";
include "../include/engine.inc.php";


// config:


// session:


// request:
$cid = $_GET["c"];


// data:
if ($cid > 0) $riga = $database->sel_record("Metadata", "MediaCode = $cid");
else $riga = array();


// logic:


// view:

include "../include/adm_header.php";
include "../include/edit.tpl.php";
include "../include/footer.php";


?>
