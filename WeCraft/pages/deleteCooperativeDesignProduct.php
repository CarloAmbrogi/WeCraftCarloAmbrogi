<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for deleting the collaboration for the design of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be the owner of the product
  //You can see this page only if the collaborating design for this product is active
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Delete cooperation"),"");
      //Receive post request to delete the collaboration for the design of this product
      $insertedProductId = $_POST['insertedProductId'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the design of this product"));
      } else {
        //Check to be the owner
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Delete the collaboration for the design of this product (also the sheet)
          deleteCooperatingDesignForThisProduct($insertedProductId);
          deleteSheetCooperatingDesignForThisProduct($insertedProductId);
          addParagraph(translate("Done"));
        } else {
          addParagraph(translate("You are not the owner of the product related to this collaboration"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProductExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$_GET["id"])){
            //Check you are the owner of the related product
            $productInfos = obtainProductInfos($_GET["id"]);
            if($_SESSION["userId"] == $productInfos["artisan"]){
              addScriptAddThisPageToCronology();
              upperPartOfThePage(translate("Delete cooperation"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              addParagraph(translate("Delete cooperation for the design for this product")."?");
              //Form to insert data to delete the cooperation for the design for this product
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Delete cooperation"));
              //End main content of this page
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You are not the owner of the product related to this collaboration"));
            }
          } else {
            upperPartOfThePage(translate("Error"),"");
            addParagraph(translate("You are not a collaborator for the design of this product"));
          }
        } else {
          upperPartOfThePage(translate("Error"),"");
          addParagraph(translate("This product doesnt exists"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        //You have missed to specify the get param id of the product
        addParagraph(translate("You have missed to specify the get param id of the product"));
      }
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
