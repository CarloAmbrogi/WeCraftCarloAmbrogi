<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Start cooperative production for this product
  //If you are the owner you can start the cooperative production (and create a group) for this product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for starting a cooperative production for this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Start cooperative production"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check that the collaboration for the cooperative production in not already setted
          $numberCollaboratorsForThisProduct = obtainNumberCollaboratorsForThisProduct($insertedProductId);
          if($numberCollaboratorsForThisProduct == 0){
            //Start cooperative production for this product
            startCooperatingProductionForThisProduct($_SESSION["userId"],$insertedProductId);
            addSheetCooperatingProductionForThisProduct($insertedProductId);
            addParagraph(translate("The collaboration for the production of this product has started"));
            addButtonLink(translate("See collaboration"),"./cooperativeProductionProduct.php?id=".urlencode($insertedProductId));
            addParagraph(translate("From the page of the collaboration youll be able to add participants to this collaboration"));
          } else {
            addParagraph(translate("The cooperative production for this product is already active"));
            addButtonLink(translate("See collaboration"),"./cooperativeProductionProduct.php?id=".urlencode($insertedProductId));
          }
        } else {
          addParagraph(translate("You are not the owner of this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Start cooperative production"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check that the collaboration for the cooperative production in not already setted
          $numberCollaboratorsForThisProduct = obtainNumberCollaboratorsForThisProduct($_GET["id"]);
          if($numberCollaboratorsForThisProduct == 0){
            //Content of this page
            addParagraph(translate("Product").": ".$productInfos["name"]);
            //Title Start cooperative production
            addTitle(translate("Start cooperative production"));
            addParagraph(translate("Start cooperative production for this product")."?");
            //Form to insert data to start the cooperative production for this product
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Confirm"));
            //End main content of this page
          } else {
            addParagraph(translate("The cooperative production for this product is already active"));
            addButtonLink(translate("See collaboration"),"./cooperativeProductionProduct.php?id=".urlencode($_GET["id"]));
          }
        } else {
          addParagraph(translate("You are not the owner of this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Start cooperative production"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
