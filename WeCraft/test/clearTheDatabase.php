<?php

  //This script clear the database
  $possibilityToExecuteThisScript = true;
  if($possibilityToExecuteThisScript == true){
    $connection = mysqli_connect("localhost","carloambrogipolimi","","my_carloambrogipolimi");
    mysqli_set_charset($connection, "utf8mb4");

    //Tables of WeCraft
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Advertisement`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Artisan`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ContentPurchase`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Customer`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Designer`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ExchangeProduct`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Product`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ProductImages`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ProductTags`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`PurchasesCronology`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ShoppingCart`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`User`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`UserImages`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`CooperativeDesignProducts`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`CooperativeDesignProjects`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`SheetProducts`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`SheetProjects`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Project`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ProjectAssignArtisans`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ProjectImages`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Messages`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`ReadMessage`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Review`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`FeedbackCollaboration`");

    //Tables of Magis
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Metadata`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`MetadataTags`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Ontology`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`POI`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Users`");
    mysqli_query($connection, "TRUNCATE TABLE `my_carloambrogipolimi`.`Tags`");
    
  }

?>
