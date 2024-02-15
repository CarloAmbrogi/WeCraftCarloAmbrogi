<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for leaving the collaboration for the production of this product
  //(get param id is te id of the product related to this collaboration)
  //You can see this page only if the collaborating production for this product is active
  //You must not to be the owner of the product
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Leave cooperation"),"");
      //Receive post request to leave the collaboration for the production of this product
      $insertedProductId = $_POST['insertedProductId'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the production of this product"));
      } else {
        //Check to not be the owner
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] != $productInfos["artisan"]){
          //leave the collaboration for the production of this product
          removeParticipantCooperatingProductionForThisProduct($_SESSION["userId"],$insertedProductId);
          addParagraph(translate("Done"));
        } else {
          addParagraph(translate("You cant leave the collaboration beacause you are the owner"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProductExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$_GET["id"])){
            //Check you aren't the owner of the related product
            $productInfos = obtainProductInfos($_GET["id"]);
            if($_SESSION["userId"] != $productInfos["artisan"]){
              addScriptAddThisPageToChronology();
              upperPartOfThePage(translate("Leave cooperation"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              addParagraph(translate("Leave this cooperation for the production for this product")."?");
              //Form to insert data to leave this cooperation for the production for this product
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Leave cooperation"));
              //End main content of this page
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You cant leave the collaboration beacause you are the owner"));
            }
          } else {
            upperPartOfThePage(translate("Error"),"");
            addParagraph(translate("You are not a collaborator for the production of this product"));
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
