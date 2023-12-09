<?php
  include dirname(__FILE__)."/../database/access.php";
  include "./../database/functions.php";

  //Load session var
  include "./../components/includes.php";
  doInitialScripts();

  //An artisan change if he is sponsorizing a product or not
  //The user must be an artisan and the product must be of another artisan
  if(isset($_GET["artisan"]) && isset($_GET["product"]) && isset($_GET["token"])){
    if(isset($_SESSION["userId"])){
      if($_GET["token"] == $_SESSION['csrftoken']){
        if($_SESSION["userId"] == $_GET["artisan"]){

          $areYouSponsoringThisProduct = isThisArtisanSponsoringThisProduct($_GET["artisan"],$_GET["product"]);
          $doesThisProductExists = doesThisProductExists($_GET["product"]);
          if($doesThisProductExists){
            $ownerOfThisProduct = obtainProductInfos($_GET["product"])["artisan"];
            if($_GET["artisan"] != $ownerOfThisProduct){
              $kindAccountArtisanShouldBeArtisan = getKindOfThisAccount($_GET["artisan"]);
              if($kindAccountArtisanShouldBeArtisan == "Artisan"){
                if($areYouSponsoringThisProduct){
                  stopSponsoringThisProduct($_GET["artisan"],$_GET["product"]);
                } else {
                  startSponsoringThisProduct($_GET["artisan"],$_GET["product"]);
                }
              }
            }
          }

        }

      }
    }
  }

  include "../database/closeConnectionDB.php";
?>
