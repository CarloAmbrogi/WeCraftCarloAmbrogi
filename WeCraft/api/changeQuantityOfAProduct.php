<?php
  include dirname(__FILE__)."/../database/access.php";
  include "./../database/functions.php";

  //Load session var
  include "./../components/includes.php";
  doInitialScripts();

  //The artisan owner of this product can increment or decrement the quantity of this product
  if(isset($_GET["kind"]) && isset($_GET["productId"])){
    if(isset($_SESSION["userId"])){

      $sql = "update `Product` set `quantity` = (`quantity` + 0) where `id` = ? and `artisan` = ?;";
      if($_GET["kind"] == "inc"){
        $sql = "update `Product` set `quantity` = (`quantity` + 1) where `id` = ? and `artisan` = ?;";
      }
      if($_GET["kind"] == "dec"){
        $sql = "update `Product` set `quantity` = (`quantity` - 1) where `id` = ? and `artisan` = ?;";
      }
      if($statement = $connectionDB->prepare($sql)){
        $statement->bind_param("ii",$_GET["productId"],$_SESSION["userId"]);
        $statement->execute();
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }

    }
  }

  include "../database/closeConnectionDB.php";
?>
