<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Remove the percentage resell of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for removing the percentage resell of this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Remove percentage resell"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check if is possible to change the percentage resell
          $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($insertedProductId);
          if($numberOtherArtisansWhoAreSellingThisExchangeProduct == 0){
            //Remove the the percentage resell of this product
            removePercentageResellOfThisProduct($insertedProductId);
            addParagraph(translate("The percentage resell for this product has been removed"));
          } else {
            addParagraph(translate("You cant change the percentage resell because other artisans are now selling this your product"));
          }
        } else {
          addParagraph(translate("You cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Remove percentage resell"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Current percentage resell
          $isThePercentageResellSetted = isThisProductReadyToBeExchanged($_GET["id"]);
          if(!$isThePercentageResellSetted){
            addParagraph(translate("Currently the percentage resell is not set for this product"));
          } else {
            $percentageResell = percentageResellOfThisProduct($_GET["id"]);
            addParagraph(translate("The current percentage resell for this product is").": ".$percentageResell."%");
            //Check if it's possible to remove the percentage resell
            $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
            if($numberOtherArtisansWhoAreSellingThisExchangeProduct == 0){
              //Form to insert data to remove the percentage resell for this product
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Remove percentage resell"));
            } else {
              addParagraph(translate("You cant remove the percentage resell because other artisans are now selling this your product"));
            }
          }
          //End main content of this page
        } else {
          addParagraph(translate("You cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Remove percentage resell"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
