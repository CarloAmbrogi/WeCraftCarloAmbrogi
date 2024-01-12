<?php

include_once("database.class.php");



// database connection configuration

$DB_SERVER = 'localhost';
$DB_DATABASE = 'my_carloambrogipolimi';

$DB_PORT = '';
///$DB_PORT = '27776';

$DB_USERNAME = 'carloambrogipolimi';
$DB_PASSWORD = '';

$database = new dbConnection($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, $DB_PORT);

// $database2 = new dbConnection(...)

?>
