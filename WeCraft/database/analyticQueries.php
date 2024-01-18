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

?>
