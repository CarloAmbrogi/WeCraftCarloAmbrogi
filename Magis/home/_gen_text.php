<?php

// libs:
include "../database/db_connect.inc.php";
///include "../include/engine.inc.php";


// config:


// session:


// data:
// $cond = "MediaCode = 55";
// $righe = $database->sel_data("Metadata", $cond);
$righe = $database->sel_data("Metadata");


// logic:


// view:

include "../include/adm_header.php";
include "../include/gen_text.tpl.php";
include "../include/footer.php";


?>
