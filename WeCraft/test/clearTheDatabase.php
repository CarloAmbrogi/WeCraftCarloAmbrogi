<?php

  //This script clear the database
  $possibilityToExecuteThisScript = false;
  if($possibilityToExecuteThisScript == true){
    $connection = mysqli_connect("localhost","carloambrogipolimi","","my_carloambrogipolimi");
    mysqli_set_charset($connection, "utf8mb4");

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
    
  }

?>
