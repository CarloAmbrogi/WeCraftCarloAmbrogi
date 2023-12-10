<?php
  include dirname(__FILE__)."/../database/access.php";

  //Get the exchange quantity of a product which is sold in the physical stor of another artisan

  if(isset($_GET["artisan"]) && isset($_GET["product"])){

    $sql = "SELECT `quantity` FROM `ExchangeProduct` WHERE `artisan` = ? and `product` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$_GET["artisan"],$_GET["product"]);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    $encodedData = json_encode($elements, JSON_UNESCAPED_UNICODE);

    print($encodedData);

  }

  include "../database/closeConnectionDB.php";
?>
