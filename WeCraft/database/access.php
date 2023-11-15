<?php
  //At the begin of a php page include this file and at the end include closeConnectionDB.php

  //Open the connection with the database

  $connectionDB = new mysqli("localhost","carloambrogipolimi","","my_carloambrogipolimi");

  if($connectionDB->connect_error){
    die("Connection error: " . $connectionDB->connect_error);
  }

  $connectionDB->set_charset('utf8mb4');

?>
