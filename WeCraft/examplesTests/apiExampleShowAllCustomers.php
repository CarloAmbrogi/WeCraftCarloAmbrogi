<?php
  include dirname(__FILE__)."/../database/access.php";

  //Show all customers

  $sql = "SELECT * FROM `Customer`";
  $results = $connectionDB->query($sql);
  while($element = $results->fetch_assoc()){
    $elements[] = $element;
  }

  $encodedData = json_encode($elements, JSON_UNESCAPED_UNICODE);

  print($encodedData);

  include dirname(__FILE__)."/../database/closeConnectionDB.php";
?>
