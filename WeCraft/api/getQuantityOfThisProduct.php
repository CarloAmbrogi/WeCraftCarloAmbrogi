<?php
  include dirname(__FILE__)."/../database/access.php";

  //Get the quantity of a product

  if(isset($_GET["productId"])){

    $productId = $_GET["productId"];

    $sql = "select quantity as quantityOfThisProduct from `Product` where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
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
