<?php
  //Functions with the database for analytic queries

  //obtain preview of artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period
  function obtainPreviewArtisansWhoArePraticallyOnlyResellingItems($minNumItems,$durationLastPeriod){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName` from `User` join `Artisan` on `User`.`id` = `Artisan`.`id` where `User`.`id` in (select `ExchangeProduct`.`artisan` from `ExchangeProduct` where `ExchangeProduct`.`quantity` > 0) and `User`.`id` not in (select t.id from (select `Artisan`.`id` as id, sum(`ContentPurchase`.`quantity`) as numSells from (`Artisan` join `Product` on `Artisan`.`id` = `Product`.`artisan`) join `ContentPurchase` on `Product`.`id` = `ContentPurchase`.`product` where `ContentPurchase`.`purchaseId` in (select `PurchasesCronology`.`id` from `PurchasesCronology` where TIMESTAMPDIFF(SECOND, `PurchasesCronology`.`timestamp`, CURRENT_TIMESTAMP()) < ?) group by `Artisan`.`id`) as t where t.numSells >= ?) order by `User`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$durationLastPeriod,$minNumItems);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName
    return $elements;
  }

  //Get number of users
  function getNumberOfUsers(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberUsers from `User`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberUsers"];
  }

  //Get number of customers
  function getNumberOfCustomers(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberCustomers from `Customer`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberCustomers"];
  }

  //Get number of artisans
  function getNumberOfArtisans(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberArtisans from `Artisan`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberArtisans"];
  }

  //Get number of designers
  function getNumberOfDesigners(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberDesigners from `Designer`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberDesigners"];
  }

  //Get number users registered on WeCraft this year
  function numberNewUsersYear($year){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberUsersYear from `User` where YEAR(`User`.`timeVerificationCode`) = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$year);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberUsersYear"];
  }

  //Get number of products
  function getNumberOfProducts(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProducts from `Product`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProducts"];
  }

  //Get number of products with a specific category
  function getNumberOfProductsWithThisCategory($category){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsWithThisCategory from `Product` where `category` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$category);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsWithThisCategory"];
  }

  //Get number products added on WeCraft this year
  function numberNewProductsYear($year){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsYear from `Product` where YEAR(`Product`.`added`) = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$year);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsYear"];
  }

  //Get number of sells
  function getNumberOfSells(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select sum(`quantity`) as numberSells from `ContentPurchase`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberSells"];
  }

  //Averange number of sells of a product
  function averangeNumberSellsOfAProduct(){
    $numProducts = getNumberOfProducts();
    $numSells = getNumberOfSells();
    if($numProducts == 0){
      return 0;
    }
    return $numSells / $numProducts;
  }

  //Get number of sells
  function getNumberOfSellsWithThisCategory($category){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select COALESCE(sum(`quantity`),0) as numberSellsWithThisCategory from `ContentPurchase` where `product` in (select `id` from `Product` where `category` = ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$category);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberSellsWithThisCategory"];
  }

  //Averange number of sells of a product with this category
  function averangeNumberSellsOfAProductWithThisCategory($category){
    $numProducts = getNumberOfProductsWithThisCategory($category);
    $numSells = getNumberOfSellsWithThisCategory($category);
    if($numProducts == 0){
      return 0;
    }
    return $numSells / $numProducts;
  }

  //Number of products not sponsored
  function numberProductsNotSponsored(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsNotSponsored from `Product` where `id` not in (select `product` from `Advertisement`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsNotSponsored"];
  }

  //Number of products witch are sponsored by a certain number of artisans (at least 1 time)
  function numberProductsSponsoredByACertainNumberOfArtisans($numberOfArtisans){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsSponsoredByACertainNumberOfArtisans from `Product` where `id` in (select t.id from (select count(*) as num, `product` as id from `Advertisement` group by `product`) as t where num = ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfArtisans);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsSponsoredByACertainNumberOfArtisans"];
  }

  //Number of products witch are sponsored by at least a certain number of artisans (at least 1 time)
  function numberProductsSponsoredByAtLeastACertainNumberOfArtisans($numberOfArtisans){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsSponsoredByAtLeastACertainNumberOfArtisans from `Product` where `id` in (select t.id from (select count(*) as num, `product` as id from `Advertisement` group by `product`) as t where num >= ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfArtisans);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsSponsoredByAtLeastACertainNumberOfArtisans"];
  }

  //Number of products not exchange sold
  function numberProductsNotExchangeSold(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsNotExchangeSold from `Product` where `id` not in (select `product` from `ExchangeProduct`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsNotExchangeSold"];
  }

  //Number of products witch are sold by a certain number of extra artisans (at least 1 time)
  function numberProductsSoldByACertainNumberOfArtisans($numberOfArtisans){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsSoldByACertainNumberOfArtisans from `Product` where `id` in (select t.id from (select count(*) as num, `product` as id from `ExchangeProduct` group by `product`) as t where num = ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfArtisans);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsSoldByACertainNumberOfArtisans"];
  }

  //Number of products witch are sold by at least a certain number of extra artisans (at least 1 time)
  function numberProductsSoldByAtLeastACertainNumberOfArtisans($numberOfArtisans){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsSoldByAtLeastACertainNumberOfArtisans from `Product` where `id` in (select t.id from (select count(*) as num, `product` as id from `ExchangeProduct` group by `product`) as t where num >= ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfArtisans);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsSoldByAtLeastACertainNumberOfArtisans"];
  }

?>
