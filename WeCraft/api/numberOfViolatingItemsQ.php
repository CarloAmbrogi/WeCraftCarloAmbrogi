<?php
  include dirname(__FILE__)."/../database/access.php";
  
  //Show the number of items in the shopping cart of this user (given the user id) witch violates the condition of exceeding the available quantity
  //Use this api as a check: if its > 0 or if the shopping cart of this user is empty, the order can't be sent
  //GET param: userId
  // example of the result:
  // [{"numberOfViolatingItemsQ":9}]

  if(isset($_GET["userId"])){

    $userId = $_GET["userId"];

    $sql = "select count(*) as numberOfViolatingItemsQ from ((select `ShoppingCart`.`product` as r from `ShoppingCart` join `Product` on `ShoppingCart`.`product` = `Product`.`id` and `ShoppingCart`.`artisan` = `Product`.`artisan` where `Product`.`quantity` < `ShoppingCart`.`quantity` and `ShoppingCart`.`customer` = ?) union (select `ShoppingCart`.`product` as r from `ShoppingCart` join `ExchangeProduct` on `ShoppingCart`.`product` = `ExchangeProduct`.`product` and `ShoppingCart`.`artisan` = `ExchangeProduct`.`artisan` where `ExchangeProduct`.`quantity` < `ShoppingCart`.`quantity` and `ShoppingCart`.`customer` = ?)) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$userId);
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
