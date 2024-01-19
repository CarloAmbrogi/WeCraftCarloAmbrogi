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

  //Number of products sold for at least a certain number of units
  function numberProductsSoldAtLeastNUnits($numMinSellsUnits){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsSoldAtLeastNUnits from (select * from ((select `id` as product, 0 as numUnitsSells from `Product` where `id` not in (select `product` from `ContentPurchase`)) union (select `product` as product, sum(`quantity`) as numUnitsSells from `ContentPurchase` group by `product`)) as t where numUnitsSells >= ?) as tt;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numMinSellsUnits);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsSoldAtLeastNUnits"];
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

  //Number of products in cooperation for the design
  function numberProductsInCooperationForTheDesign(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsInCooperationForTheDesign from (select * from `CooperativeDesignProducts` group by `product`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProductsInCooperationForTheDesign"];
  }

  //Number of products not in cooperation for the design
  function numberProductsNotInCooperationForTheDesign(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsNotInCooperationForTheDesign from `Product` where `id` not in (select `product` from `CooperativeDesignProducts`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProductsNotInCooperationForTheDesign"];
  }

  //Number of cooperations for products with a certain number of collaborators
  function numberCooperationsForProductsWithACertainNumberOfCollaborations($numberOfCollaborators){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsWithThisNumberOfCollaborators from (select id from (select `product` as id, count(*) as num from `CooperativeDesignProducts` group by `product`) as t where t.num = ?) as tt;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfCollaborators);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCooperationsWithThisNumberOfCollaborators"];
  }

  //Number of cooperations for products with at least a certain number of collaborators
  function numberCooperationsForProductsWithAtLeastACertainNumberOfCollaborations($numberOfCollaborators){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsWithAtLeastThisNumberOfCollaborators from (select id from (select `product` as id, count(*) as num from `CooperativeDesignProducts` group by `product`) as t where t.num >= ?) as tt;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$numberOfCollaborators);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCooperationsWithAtLeastThisNumberOfCollaborators"];
  }

  //Number cooperations for the design of a product with at least a designer
  function numberCooperationsProductWithADesigner(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsProductWithADesigner from (select * from `CooperativeDesignProducts` where `user` in (select `id` from `Designer`) group by `product`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCooperationsProductWithADesigner"];
  }

  //Number cooperations for the design of a product without a designer
  function numberCooperationsProductWithoutADesigner(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsProductWithoutADesigner from (select t.product from (select `product` as product from `CooperativeDesignProducts` group by `product`) as t where t.product not in (select `product` from `CooperativeDesignProducts` where `user` in (select `id` from `Designer`))) as tt;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCooperationsProductWithoutADesigner"];
  }

  //Sum number of cooperation for the design of a product for each artisan
  function sumNumberCooperationProductsArtisans(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProductsArtisans from `Artisan` join `CooperativeDesignProducts` on `Artisan`.`id` = `CooperativeDesignProducts`.`user`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["sumNumberCooperationProductsArtisans"];
  }

  //Sum number of cooperation for the design of a product for each designer
  function sumNumberCooperationProductsDesigners(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProductsDesigners from `Designer` join `CooperativeDesignProducts` on `Designer`.`id` = `CooperativeDesignProducts`.`user`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["sumNumberCooperationProductsDesigners"];
  }

  //Averange number of products for which an artisan is collaborating for the design
  function averangeNumberProductsForWhichArtisanCollaborating(){
    $n = getNumberOfArtisans();
    $d = sumNumberCooperationProductsArtisans();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of products for which a designer is collaborating for the design
  function averangeNumberProductsForWhichDesignerCollaborating(){
    $n = getNumberOfDesigners();
    $d = sumNumberCooperationProductsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of products for which an artisan or a designer is collaborating for the design
  function averangeNumberProductsForWhichArtisanDesignerCollaborating(){
    $n = getNumberOfArtisans() + getNumberOfDesigners();
    $d = sumNumberCooperationProductsArtisans() + sumNumberCooperationProductsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Number of projects that have been completed within a certain time range
  function numberCompletedProjectsInCertainTimeRange($min,$max){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCompletedProjectsInCertainTimeRange from `Project` where TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= ? and TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) <= ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$min,$max);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCompletedProjectsInCertainTimeRange"];
  }

  //Number of projects that have been completed in at least a certain time range
  function numberCompletedProjectsInAtLeastCertainTimeRange($min){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCompletedProjectsInAtLeastCertainTimeRange from `Project` where TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$min);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numCompletedProjectsInAtLeastCertainTimeRange"];
  }

  //Number projects not assigned to any artisan
  function numberProjectsNotAssigned(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsNotAssigned from `Project` where `id` not in (select `project` from `ProjectAssignArtisans`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsNotAssigned"];
  }

  //Number projects assigned but not claimed
  function numberProjectsAssignedNotClaimed(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsAssignedNotClaimed from `Project` where `claimedByThisArtisan` is null and `id` in (select `project` from `ProjectAssignArtisans`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsAssignedNotClaimed"];
  }

  //Number projects claimed but not confirmed
  function numberProjectsClaimedNotConfirmed(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsClaimedNotConfirmed from `Project` where `claimedByThisArtisan` is not null and `confirmedByTheCustomer` = 0;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsClaimedNotConfirmed"];
  }

  //Number projects confirmed not completed
  function numberProjectsConfirmedNotCompleted(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsConfirmedNotCompleted from `Project` where `confirmedByTheCustomer` = 1 and `timestampReady` is null;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsConfirmedNotCompleted"];
  }

  //Number projects completed
  function numberProjectsCompleted(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsCompleted from `Project` where `timestampReady` is not null;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsCompleted"];
  }

  //Number of projects in cooperation for the design
  function numberProjectsInCooperationForTheDesign(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsInCooperationForTheDesign from (select * from `CooperativeDesignProjects` group by `project`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsInCooperationForTheDesign"];
  }

  //Number of projects not in cooperation for the design
  function numberProjectsNotInCooperationForTheDesign(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsNotInCooperationForTheDesign from `Project` where `id` not in (select `project` from `CooperativeDesignProjects`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsNotInCooperationForTheDesign"];
  }

    //Number of cooperations for projects with a certain number of collaborators
    function numberCooperationsForProjectsWithACertainNumberOfCollaborations($numberOfCollaborators){
      $connectionDB = $GLOBALS['$connectionDB'];
      $sql = "select count(*) as numCooperationsWithThisNumberOfCollaborators from (select id from (select `project` as id, count(*) as num from `CooperativeDesignProjects` group by `project`) as t where t.num = ?) as tt;";
      if($statement = $connectionDB->prepare($sql)){
        $statement->bind_param("i",$numberOfCollaborators);
        $statement->execute();
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }
  
      $results = $statement->get_result();
      while($element = $results->fetch_assoc()){
        $elements[] = $element;
      }
  
      return $elements[0]["numCooperationsWithThisNumberOfCollaborators"];
    }
  
    //Number of cooperations for projects with at least a certain number of collaborators
    function numberCooperationsForProjectsWithAtLeastACertainNumberOfCollaborations($numberOfCollaborators){
      $connectionDB = $GLOBALS['$connectionDB'];
      $sql = "select count(*) as numCooperationsWithAtLeastThisNumberOfCollaborators from (select id from (select `project` as id, count(*) as num from `CooperativeDesignProjects` group by `project`) as t where t.num >= ?) as tt;";
      if($statement = $connectionDB->prepare($sql)){
        $statement->bind_param("i",$numberOfCollaborators);
        $statement->execute();
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }
  
      $results = $statement->get_result();
      while($element = $results->fetch_assoc()){
        $elements[] = $element;
      }
  
      return $elements[0]["numCooperationsWithAtLeastThisNumberOfCollaborators"];
    }

  //Sum number of cooperation for the design of a project for each artisan
  function sumNumberCooperationProjectsArtisans(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProjectsArtisans from `Artisan` join `CooperativeDesignProjects` on `Artisan`.`id` = `CooperativeDesignProjects`.`user`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["sumNumberCooperationProjectsArtisans"];
  }

  //Sum number of cooperation for the design of a project for each designer
  function sumNumberCooperationProjectsDesigners(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProjectsDesigners from `Designer` join `CooperativeDesignProjects` on `Designer`.`id` = `CooperativeDesignProjects`.`user`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["sumNumberCooperationProjectsDesigners"];
  }

  //Averange number of projects for which an artisan is collaborating for the design
  function averangeNumberProjectsForWhichArtisanCollaborating(){
    $n = getNumberOfArtisans();
    $d = sumNumberCooperationProjectsArtisans();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of projects for which a designer is collaborating for the design
  function averangeNumberProjectsForWhichDesignerCollaborating(){
    $n = getNumberOfDesigners();
    $d = sumNumberCooperationProjectsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of projects for which an artisan or a designer is collaborating for the design
  function averangeNumberProjectsForWhichArtisanDesignerCollaborating(){
    $n = getNumberOfArtisans() + getNumberOfDesigners();
    $d = sumNumberCooperationProjectsArtisans() + sumNumberCooperationProjectsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }
  
?>
