<?php

  $connectionDB = new mysqli("localhost","carloambrogipolimi","","my_carloambrogipolimi");

  if($connectionDB->connect_error){
    die("Connection error: " . $connectionDB->connect_error);
  }

  $connectionDB->set_charset('utf8mb4');

?>
