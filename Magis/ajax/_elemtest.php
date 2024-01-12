<?php

/*

// accesso alla sessione:
include ("../engine/" . "sessions.inc.php");
sn_enable();

// accesso ai dati:
include ("../database/" . "database.inc.php");

*/



$param1 = $_POST["p1"];
$param2 = $_POST["p2"];
$securecode = $_POST["s1"];

if ($securecode != '43210') die ('error: invalid call');


/*
$settings = sn_read('settings');
$uid = $settings["userid"];
*/




/*
$sql = "select name from model where model_id = '$item_id' and user_id='$uid'";
$dbCon = new dbConnection();
$dati = $dbCon->db_record( $sql );
$dbCon->close();
if ($dati) {
  $output = $dati["name"];
}
*/


?>
param.1 = <?= $param1 ?> <br>
param.2 = <?= $param2 ?> <br>
<br>

