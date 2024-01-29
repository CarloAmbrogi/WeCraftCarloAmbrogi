<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for removing partecipants (artisan or designer) as collaborators for the design of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be the owner of the product
  //You can see this page only if the collaborating design for this product is active
  //You cant remove yourself
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative design"),"cookieBack");
      //Receive post request to remove partecipants to the cooperative design for this product
      $insertedProductId = $_POST['insertedProductId'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the design of this product"));
      } else {
        //Check to be the owner of the related product
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Remove partecipants
          $previewArtisansCollaboratorsOfThisProduct = obtainPreviewArtisansCollaboratorsOfThisProduct($insertedProductId);
          $previewDesignersCollaboratorsOfThisProduct = obtainPreviewDesignersCollaboratorsOfThisProduct($insertedProductId);
          $removedAtLeastAPartecipant = false;
          foreach ($previewArtisansCollaboratorsOfThisProduct as &$singleArtisanPreview){
            $idOfThisPartecipant = $singleArtisanPreview["id"];
            $postOfThisPartecipant = $_POST['partecipant'.$idOfThisPartecipant];
            if($postOfThisPartecipant == true){
              removePartecipantCooperatingDesignForThisProduct($idOfThisPartecipant,$insertedProductId);
              $removedAtLeastAPartecipant = true;
              //Send a notification to the user
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$idOfThisPartecipant,"You have been removed to the collaboration for the design of this product","product",$insertedProductId);
            }
          }
          foreach ($previewDesignersCollaboratorsOfThisProduct as &$singleDesignerPreview){
            $idOfThisPartecipant = $singleDesignerPreview["id"];
            $postOfThisPartecipant = $_POST['partecipant'.$idOfThisPartecipant];
            if($postOfThisPartecipant == true){
              removePartecipantCooperatingDesignForThisProduct($idOfThisPartecipant,$insertedProductId);
              $removedAtLeastAPartecipant = true;
              //Send a notification to the user
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$idOfThisPartecipant,"You have been removed to the collaboration for the design of this product","product",$insertedProductId);
            }
          }
          if($removedAtLeastAPartecipant){
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("No partecipant has been removed"));
          }
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
              upperPartOfThePage(translate("Cooperative design"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              //Title Remove partecipants
              addTitle(translate("Remove partecipants"));
              $numberCollaboratorsForThisProduct = obtainNumberCollaboratorsForThisProduct($_GET["id"]);
              if($numberCollaboratorsForThisProduct >= 2){
                //Form to insert data to remove partecipants
                startForm1();
                addParagraphInAForm(translate("Select the partecipants to remove"));
                startForm2($_SERVER['PHP_SELF']);
                $previewArtisansCollaboratorsOfThisProduct = obtainPreviewArtisansCollaboratorsOfThisProduct($_GET["id"]);
                $previewDesignersCollaboratorsOfThisProduct = obtainPreviewDesignersCollaboratorsOfThisProduct($_GET["id"]);
                foreach ($previewArtisansCollaboratorsOfThisProduct as &$singleArtisanPreview){
                  ?>
                    <ul class="list-group">
                      <li class="list-group-item">
                        <div for="partecipant<?= $singleArtisanPreview["id"] ?>" class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="partecipant<?= $singleArtisanPreview["id"] ?>" name="partecipant<?= $singleArtisanPreview["id"] ?>">
                          <?php addParagraph($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]) ?>
                        </div>
                      </li>
                    </ul>
                  <?php
                }
                foreach ($previewDesignersCollaboratorsOfThisProduct as &$singleDesignerPreview){
                  ?>
                    <ul class="list-group">
                      <li class="list-group-item">
                        <div for="partecipant<?= $singleDesignerPreview["id"] ?>" class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="partecipant<?= $singleDesignerPreview["id"] ?>" name="partecipant<?= $singleDesignerPreview["id"] ?>">
                          <?php addParagraph($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]) ?>
                        </div>
                      </li>
                    </ul>
                  <?php
                }
                addHiddenField("insertedProductId",$_GET["id"]);
                endForm(translate("Submit"));
              } else {
                addParagraph(translate("There arent partecipants to remove"));
              }
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
