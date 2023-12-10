<?php
  include dirname(__FILE__)."/../database/access.php";
  include "./../database/functions.php";

  //Load session var
  include "./../components/includes.php";
  doInitialScripts();

  //An artisan not owner of this product can increment or decrement the quantity of this exchange product
  if(isset($_GET["kind"]) && isset($_GET["productId"]) && isset($_GET["token"])){
    if(isset($_SESSION["userId"])){
      if($_GET["token"] == $_SESSION['csrftoken']){

        $sql = "update `ExchangeProduct` set `quantity` = (`quantity` + 0) where `product` = ? and `artisan` = ?;";
        if($_GET["kind"] == "inc"){
          $sql = "update `ExchangeProduct` set `quantity` = (`quantity` + 1) where `product` = ? and `artisan` = ?;";
        }
        if($_GET["kind"] == "dec"){
          $sql = "update `ExchangeProduct` set `quantity` = (`quantity` - 1) where `product` = ? and `artisan` = ?;";
        }
        if($statement = $connectionDB->prepare($sql)){
          $statement->bind_param("ii",$_GET["productId"],$_SESSION["userId"]);
          $statement->execute();
        } else {
          echo "Error not possible execute the query: $sql. " . $connectionDB->error;
        }

      }
    }
  }

  include "../database/closeConnectionDB.php";
?>
