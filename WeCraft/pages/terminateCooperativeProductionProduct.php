<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for terminating the collaboration for the production of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be the owner of the product
  //You can see this page only if the collaborating production for this product is active
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Terminate cooperation"),"");
      //Receive post request to terminate the collaboration for the production of this product
      $insertedProductId = $_POST['insertedProductId'];
      $insertedFeedback = $_POST['insertedFeedback'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedFeedback == ""){
        addParagraph(translate("You have missed to insert the feedback"));
      } else if(strlen($insertedFeedback) > 2046){
        addParagraph(translate("The feedback is too long"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the production of this product"));
      } else {
        //Check to be the owner
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Save the feedback of the owner of the product
          saveFeedbackCollaboration($_SESSION["userId"], "owner", $insertedProductId, "product", $insertedFeedback);
          //Send a notification to all the other partecipants to write a feedback about how has gone the collaboration
          $previewArtisansCollaboratorsOfThisProduct = obtainPreviewArtisansCollaboratorsOfThisProduct($insertedProductId);
          $previewDesignersCollaboratorsOfThisProduct = obtainPreviewDesignersCollaboratorsOfThisProduct($insertedProductId);
          foreach($previewArtisansCollaboratorsOfThisProduct as &$singleArtisanPreview){
            $toWho = $singleArtisanPreview["id"];
            sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$toWho,"The collaboration for this product has terminated please send now a feedback about how has gone the collaboration to improve WeCraft","feedbackCollProd",$insertedProductId);
          }
          foreach($previewDesignersCollaboratorsOfThisProduct as &$singleDesignerPreview){
            $toWho = $singleDesignerPreview["id"];
            sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$toWho,"The collaboration for this product has terminated please send now a feedback about how has gone the collaboration to improve WeCraft","feedbackCollProd",$insertedProductId);
          }
          //Terminate the collaboration for the production of this product (also the sheet)
          terminateCooperatingProductionForThisProduct($insertedProductId);
          deleteSheetCooperatingProductionForThisProduct($insertedProductId);
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
          if(isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$_GET["id"])){
            //Check you are the owner of the related product
            $productInfos = obtainProductInfos($_GET["id"]);
            if($_SESSION["userId"] == $productInfos["artisan"]){
              addScriptAddThisPageToChronology();
              upperPartOfThePage(translate("Terminate cooperation"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              addParagraph(translate("Terminate cooperation for the production for this product")."?");
              addParagraph(translate("Provide also a feedback about how has gone this collaboration"));
              //Form to insert data to terminate the cooperation for the production for this product
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addLongTextField(translate("Feedback"),"insertedFeedback",2046);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Terminate cooperation"));
              ?>
                <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedFeedback = document.getElementById('insertedFeedback');

                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedFeedback.value === ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the feedback") ?>");
                    }
                  }
                </script>
              <?php
              //End main content of this page
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You are not the owner of the product related to this collaboration"));
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
