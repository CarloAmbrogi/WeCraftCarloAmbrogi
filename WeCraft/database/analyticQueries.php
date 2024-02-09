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

  //Number of products in cooperation for the production
  function numberProductsInCooperationForTheProduction(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsInCooperationForTheDesign from (select * from `CooperativeProductionProducts` group by `product`) as t;";
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

  //Number of products not in cooperation for the production
  function numberProductsNotInCooperationForTheProduction(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsNotInCooperationForTheDesign from `Product` where `id` not in (select `product` from `CooperativeProductionProducts`);";
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
    $sql = "select count(*) as numCooperationsWithThisNumberOfCollaborators from (select id from (select `product` as id, count(*) as num from `CooperativeProductionProducts` group by `product`) as t where t.num = ?) as tt;";
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
    $sql = "select count(*) as numCooperationsWithAtLeastThisNumberOfCollaborators from (select id from (select `product` as id, count(*) as num from `CooperativeProductionProducts` group by `product`) as t where t.num >= ?) as tt;";
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

  //Number cooperations for the production of a product with at least a designer
  function numberCooperationsProductWithADesigner(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsProductWithADesigner from (select * from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`) group by `product`) as t;";
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

  //Number cooperations for the production of a product without a designer
  function numberCooperationsProductWithoutADesigner(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCooperationsProductWithoutADesigner from (select t.product from (select `product` as product from `CooperativeProductionProducts` group by `product`) as t where t.product not in (select `product` from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`))) as tt;";
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

  //Sum number of cooperation for the production of a product for each artisan
  function sumNumberCooperationProductsArtisans(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProductsArtisans from `Artisan` join `CooperativeProductionProducts` on `Artisan`.`id` = `CooperativeProductionProducts`.`user`;";
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

  //Sum number of cooperation for the production of a product for each designer
  function sumNumberCooperationProductsDesigners(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProductsDesigners from `Designer` join `CooperativeProductionProducts` on `Designer`.`id` = `CooperativeProductionProducts`.`user`;";
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

  //Averange number of products for which an artisan is collaborating for the production
  function averangeNumberProductsForWhichArtisanCollaborating(){
    $n = getNumberOfArtisans();
    $d = sumNumberCooperationProductsArtisans();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of products for which a designer is collaborating for the production
  function averangeNumberProductsForWhichDesignerCollaborating(){
    $n = getNumberOfDesigners();
    $d = sumNumberCooperationProductsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of products for which an artisan or a designer is collaborating for the production
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

  //Number projects not presented to any artisan
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

  //Number projects presented but not claimed
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

  //Number of projects in cooperation for the production
  function numberProjectsInCooperationForTheProduction(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsInCooperationForTheProduction from (select * from `CooperativeProductionProjects` group by `project`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsInCooperationForTheProduction"];
  }

  //Number of projects not in cooperation for the production
  function numberProjectsNotInCooperationForTheProduction(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsNotInCooperationForTheProduction from `Project` where `id` not in (select `project` from `CooperativeProductionProjects`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsNotInCooperationForTheProduction"];
  }

    //Number of cooperations for projects with a certain number of collaborators
    function numberCooperationsForProjectsWithACertainNumberOfCollaborations($numberOfCollaborators){
      $connectionDB = $GLOBALS['$connectionDB'];
      $sql = "select count(*) as numCooperationsWithThisNumberOfCollaborators from (select id from (select `project` as id, count(*) as num from `CooperativeProductionProjects` group by `project`) as t where t.num = ?) as tt;";
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
      $sql = "select count(*) as numCooperationsWithAtLeastThisNumberOfCollaborators from (select id from (select `project` as id, count(*) as num from `CooperativeProductionProjects` group by `project`) as t where t.num >= ?) as tt;";
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

  //Sum number of cooperation for the production of a project for each artisan
  function sumNumberCooperationProjectsArtisans(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProjectsArtisans from `Artisan` join `CooperativeProductionProjects` on `Artisan`.`id` = `CooperativeProductionProjects`.`user`;";
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

  //Sum number of cooperation for the production of a project for each designer
  function sumNumberCooperationProjectsDesigners(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as sumNumberCooperationProjectsDesigners from `Designer` join `CooperativeProductionProjects` on `Designer`.`id` = `CooperativeProductionProjects`.`user`;";
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

  //Averange number of projects for which an artisan is collaborating for the production
  function averangeNumberProjectsForWhichArtisanCollaborating(){
    $n = getNumberOfArtisans();
    $d = sumNumberCooperationProjectsArtisans();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of projects for which a designer is collaborating for the production
  function averangeNumberProjectsForWhichDesignerCollaborating(){
    $n = getNumberOfDesigners();
    $d = sumNumberCooperationProjectsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Averange number of projects for which an artisan or a designer is collaborating for the production
  function averangeNumberProjectsForWhichArtisanDesignerCollaborating(){
    $n = getNumberOfArtisans() + getNumberOfDesigners();
    $d = sumNumberCooperationProjectsArtisans() + sumNumberCooperationProjectsDesigners();
    if($d == 0){
      return 0;
    }
    return $n / $d;
  }

  //Numbero of projects public
  function numberOfProjectsPublic(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsPublic from `Project` where `isPublic` = 1;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsPublic"];
  }

  //Numbero of projects private
  function numberOfProjectsPrivate(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProjectsPrivate from `Project` where `isPublic` = 0;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProjectsPrivate"];
  }

  //Get number of users registered from X to X
  function getNumberOfUsersHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberUsers from `User` where `timeVerificationCode` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
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

  //Get number of customers registered from X to X
  function getNumberOfCustomersHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberCustomers from `Customer` where `id` in (select `id` from `User` where `timeVerificationCode` BETWEEN ? AND ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
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

  //Get number of artisans registered from X to X
  function getNumberOfArtisansHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberArtisans from `Artisan` where `id` in (select `id` from `User` where `timeVerificationCode` BETWEEN ? AND ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
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

  //Get number of designers registered from X to X
  function getNumberOfDesignersHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberDesigners from `Designer` where `id` in (select `id` from `User` where `timeVerificationCode` BETWEEN ? AND ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
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

  //Get number of products added from X to X
  function getNumberOfProductsHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProducts from `Product` where `added` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
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

  //Get number of products with a specific category added from X to X
  function getNumberOfProductsWithThisCategoryHistorical($category,$start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsWithThisCategory from `Product` where `category` = ? and `added` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("sss",$category,$start,$end);
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

  //Number of products added from X to X where now are in cooperation for the production
  function numberProductsInCooperationForTheProductionHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsInCooperationForTheProduction from (select * from `CooperativeProductionProducts` where `product` in (select `id` from `Product` where `added` BETWEEN ? AND ?) group by `product`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProductsInCooperationForTheProduction"];
  }

  //Number of products added from X to X where now aren't in cooperation for the production
  function numberProductsNotInCooperationForTheProductionHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProductsNotInCooperationForTheProduction from `Product` where `id` not in (select `product` from `CooperativeProductionProducts`) and `id` in (select `id` from `Product` where `added` BETWEEN ? AND ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProductsNotInCooperationForTheProduction"];
  }

  //Max product id
  function maxProductId(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select max(`id`) as maxProductId from `Product`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["maxProductId"];
  }

  //Obtain CooperativeProductionProductsTrig
  function obtainCooperativeProductionProductsTrigLimitDate($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `CooperativeProductionProductsTrig`.`id`,`CooperativeProductionProductsTrig`.`user`,`CooperativeProductionProductsTrig`.`product`,`CooperativeProductionProductsTrig`.`action`,`CooperativeProductionProductsTrig`.`timestamp` from `CooperativeProductionProductsTrig` where `CooperativeProductionProductsTrig`.`timestamp` BETWEEN ? AND ? order by `CooperativeProductionProductsTrig`.`id` ASC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id user product action timestamp
    return $elements;
  }

  //is this product added from X to X
  function isThisProductAddedBetweenDates($productId,$start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as isThisProductAddedBetweenDates from `Product` where `id` = ? and `added` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$productId,$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["isThisProductAddedBetweenDates"];
  }

  //Number products added from X to X which have never been in collaboration for the production
  function numberProductsAddedBetweenDatesNeverBeenCollaboration($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsNeverBeenCollaboration from `Product` where `added` BETWEEN ? AND ? and `id` not in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert');";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsNeverBeenCollaboration"];
  }

  //Number products added from X to X which have been in collaboration for the production but never with a designer
  function numberProductsAddedBetweenDatesBeenCollaborationNeverDesinger($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsBeenCollaborationNeverDesigner from `Product` where `added` BETWEEN ? AND ? and `id` in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') and `id` not in (select `product` from `CooperativeProductionProductsTrig` where `user` in (select `id` from `Designer`));";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsBeenCollaborationNeverDesigner"];
  }

  //Number products added from X to X which have been in collaboration for the production with a designer
  function numberProductsAddedBetweenDatesBeenCollaborationDesinger($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsBeenCollaborationDesigner from `Product` where `added` BETWEEN ? AND ? and `id` in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') and `id` in (select `product` from `CooperativeProductionProductsTrig` where `user` in (select `id` from `Designer`));";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsBeenCollaborationDesigner"];
  }

  //Number of projects that have been completed within a certain time range (projects confirmed between X and X)
  function numberCompletedProjectsInCertainTimeRangeHistorical($min,$max,$start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCompletedProjectsInCertainTimeRange from `Project` where TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= ? and TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) <= ? and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiss",$min,$max,$start,$end);
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

  //Number of projects that have been completed in at least a certain time range (projects confirmed between X and X)
  function numberCompletedProjectsInAtLeastCertainTimeRangeHistorical($min,$start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numCompletedProjectsInAtLeastCertainTimeRange from `Project` where TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= ? and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$min,$start,$end);
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

  //Number of projects in cooperation for the production which have been confirmed between X and X (also completed projects)
  function numberProjectsInCooperationForTheProductionConfirmedBetweenDates($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsInCooperationForTheProduction from (select * from `CooperativeProductionProjects` where `project` in (select `id` from `Project` where `timestampPurchase` is not NULL and `timestampPurchase` BETWEEN ? AND ?) group by `project`) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsInCooperationForTheProduction"];
  }

  //Number of projects not in cooperation for the production which have been confirmed between X and X (also completed projects)
  function numberProjectsNotInCooperationForTheProductionConfirmedBetweenDates($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numProjectsNotInCooperationForTheProduction from `Project` where `id` not in (select `project` from `CooperativeProductionProjects`) and `timestampPurchase` is not NULL and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numProjectsNotInCooperationForTheProduction"];
  }

  //Max project id
  function maxProjectId(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select max(`id`) as maxProjectId from `Project`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["maxProjectId"];
  }

  //Obtain CooperativeProductionProjectsTrig
  function obtainCooperativeProductionProjectsTrigLimitDate($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `CooperativeProductionProjectsTrig`.`id`,`CooperativeProductionProjectsTrig`.`user`,`CooperativeProductionProjectsTrig`.`project`,`CooperativeProductionProjectsTrig`.`action`,`CooperativeProductionProjectsTrig`.`timestamp` from `CooperativeProductionProjectsTrig` where `CooperativeProductionProjectsTrig`.`timestamp` BETWEEN ? AND ? order by `CooperativeProductionProjectsTrig`.`id` ASC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id user project action timestamp
    return $elements;
  }

  //Collaboration production product score in years (each time an action to add a person is performed it counts as one point)
  //(not count duplicates)
  function collaborationProductionProductScoreYears($year){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as collaborationProductionProductScoreYears from (select * from `CooperativeProductionProductsTrig` where YEAR(`CooperativeProductionProductsTrig`.`timestamp`) = ? and `action` = 'insert' group by `user`,`product`) as t;";
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

    return $elements[0]["collaborationProductionProductScoreYears"];
  }

  //Collaboration production project score in years (each time an action to add a person is performed it counts as one point)
  //(not count duplicates)
  function collaborationProductionProjectScoreYears($year){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as collaborationProductionProjectScoreYears from (select * from `CooperativeProductionProjectsTrig` where YEAR(`CooperativeProductionProjectsTrig`.`timestamp`) = ? and `action` = 'insert' group by `user`,`project`) as t;";
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

    return $elements[0]["collaborationProductionProjectScoreYears"];
  }

  //Number of projects completed in time
  function numberOfProjectsCompletedInTime(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsCompletedInTime from `Project` where `timestampReady` is not null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) < `estimatedTime`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsCompletedInTime"];
  }

  //Number of projects completed but not in time
  function numberOfProjectsNotCompletedInTime(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsNotCompletedInTime from `Project` where `timestampReady` is not null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= `estimatedTime`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsNotCompletedInTime"];
  }

  //Number of projects not completed and in delay
  function numberOfProjectsNotCompletedInDelay(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsNotCompletedInDelay from `Project` where `timestampReady` is null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, CURRENT_TIMESTAMP()) >= `estimatedTime`);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsNotCompletedInDelay"];
  }

  //Number of projects (confirmed between X and X) completed in time
  function numberOfProjectsCompletedInTimeHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsCompletedInTime from `Project` where `timestampReady` is not null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) < `estimatedTime`) and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsCompletedInTime"];
  }

  //Number of projects completed but not in time
  function numberOfProjectsNotCompletedInTimeHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsNotCompletedInTime from `Project` where `timestampReady` is not null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, `timestampReady`) >= `estimatedTime`) and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsNotCompletedInTime"];
  }

  //Number of projects not completed and in delay
  function numberOfProjectsNotCompletedInDelayHistorical($start,$end){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfProjectsNotCompletedInDelay from `Project` where `timestampReady` is null and `timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `timestampPurchase`, CURRENT_TIMESTAMP()) >= `estimatedTime`) and `timestampPurchase` BETWEEN ? AND ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$start,$end);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfProjectsNotCompletedInDelay"];
  }
  
?>
