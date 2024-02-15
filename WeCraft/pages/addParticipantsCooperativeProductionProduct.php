<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for adding a participant (artisan or designer) as collaborator for the production of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be the owner of the product
  //You can see this page only if the collaborating production for this product is active
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative production"),"cookieBack");
      //Receive post request to add a new participant to the cooperative production for this product
      $insertedProductId = $_POST['insertedProductId'];
      $insertedParticipant = trim($_POST['insertedParticipant']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the production of this product"));
      } else if($insertedParticipant == ""){
        addParagraph(translate("You have missed to insert the new participant"));
      } else if(strlen($insertedParticipant) > 49){
        addParagraph(translate("The email address of the new participant is too long"));
      } else if(!isValidEmail($insertedParticipant)){
        addParagraph(translate("The email address of this new participant is not valid"));
      } else {
        //Check to be the owner
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check that the user you are going to add exists and it is an artisan or a designer
          //and is not already collaborating for the production of this product
          if(doesThisUserGivenEmailExists($insertedParticipant)){
            $userToAdd = idUserWithThisEmail($insertedParticipant);
            $kindUserToAdd = getKindOfThisAccount($userToAdd);
            if($kindUserToAdd == "Artisan" || $kindUserToAdd == "Designer"){
              if(!isThisUserCollaboratingForTheProductionOfThisProduct($userToAdd,$insertedProductId)){
                //The new user is added to the collaboration of this product
                startCooperatingProductionForThisProduct($userToAdd,$insertedProductId);
                //Send a notification to the user
                sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$userToAdd,"You have been added to the collaboration for the production of this product","product",$insertedProductId);
                //Show that the user has been added
                $userInfos = obtainUserInfos($userToAdd);
                addParagraph(translate("The user")." ".$userInfos["name"]." ".$userInfos["surname"]." (".$userInfos["email"].") ".translate("has been added"));
              } else {
                addParagraph(translate("The user is already collaborating for the production of this product"));
              }
            } else {
              addParagraph(translate("The user you are going to add is not an artisan or a designer"));
            }
          } else {
            addParagraph(translate("The user you are going to add doesnt exists"));
          }
          addButtonLink(translate("Add another user"),"./addParticipantsCooperativeProductionProduct.php?id=".urlencode($insertedProductId));
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
              upperPartOfThePage(translate("Cooperative production"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              //Form to insert data to add the new participant
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addShortTextField(translate("Insert the email address of the new participant to add for the collaboration for the production of this product"),"insertedParticipant",49);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Submit"));
              //Suggested artisans and designers
              addTitle(translate("Suggested artisans and designers"));
              //Artisans with which you have collaborated before
              addParagraph(translate("Artisans with which you have collaborated before"));
              $previewArtisansWithWitchYouHaveWorkedBefore = obtainPreviewArtisansWithWitchYouHaveWorkedBefore($_SESSION["userId"]);
              $firstArtisan = true;
              $needToEndCardGrid = false;
              foreach($previewArtisansWithWitchYouHaveWorkedBefore as &$singleArtisanPreview){
                if(!isThisUserCollaboratingForTheProductionOfThisProduct($singleArtisanPreview["id"],$_GET["id"])){
                  if($firstArtisan){
                    startCardGrid();
                    $needToEndCardGrid = true;
                    $firstArtisan = false;
                  }
                  $fileImageToVisualize = genericUserImage;
                  if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                    $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
                  }
                  addACardFunctionToCallAfter("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artisan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"],"insertInInsertedParticipant",$singleArtisanPreview["email"]);
                }
              }
              if($needToEndCardGrid == true){
                endCardGrid();
              } else {
                addParagraph(translate("No result"));
              }
              //Designers with which you have collaborated before
              addParagraph(translate("Designers with which you have collaborated before"));
              $previewDesignersWithWitchYouHaveWorkedBefore = obtainPreviewDesignersWithWitchYouHaveWorkedBefore($_SESSION["userId"]);
              $firstDesigner = true;
              $needToEndCardGrid = false;
              foreach($previewDesignersWithWitchYouHaveWorkedBefore as &$singleDesignerPreview){
                if(!isThisUserCollaboratingForTheProductionOfThisProduct($singleDesignerPreview["id"],$_GET["id"])){
                  if($firstDesigner){
                    startCardGrid();
                    $needToEndCardGrid = true;
                    $firstDesigner = false;
                  }
                  $fileImageToVisualize = genericUserImage;
                  if(isset($singleDesignerPreview['icon']) && ($singleDesignerPreview['icon'] != null)){
                    $fileImageToVisualize = blobToFile($singleDesignerPreview["iconExtension"],$singleDesignerPreview['icon']);
                  }
                  addACardFunctionToCallAfter("./designer.php?id=".urlencode($singleDesignerPreview["id"]),$fileImageToVisualize,htmlentities($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]),translate("Designer"),"","insertInInsertedParticipant",$singleDesignerPreview["email"]);
                }
              }
              if($needToEndCardGrid == true){
                endCardGrid();
              } else {
                addParagraph(translate("No result"));
              }
              //Artisans near to your position
              addParagraph(translate("Artisans near to your position"));
              $yourArtisanInfos = obtainArtisanInfos($_SESSION["userId"]);
              $previewArtisansNearToYourPosition = obtainPreviewArtisansNearPosition($yourArtisanInfos["latitude"],$yourArtisanInfos["longitude"],$_SESSION["userId"],8);
              $firstArtisan = true;
              $needToEndCardGrid = false;
              foreach($previewArtisansNearToYourPosition as &$singleArtisanPreview){
                if(!isThisUserCollaboratingForTheProductionOfThisProduct($singleArtisanPreview["id"],$_GET["id"])){
                  if($firstArtisan){
                    startCardGrid();
                    $needToEndCardGrid = true;
                    $firstArtisan = false;
                  }
                  $fileImageToVisualize = genericUserImage;
                  if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                    $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
                  }
                  addACardFunctionToCallAfter("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Distance").": ".$singleArtisanPreview["distance"],"insertInInsertedParticipant",$singleArtisanPreview["email"]);
                }
              }
              if($needToEndCardGrid == true){
                endCardGrid();
              } else {
                addParagraph(translate("No result"));
              }
              //Artisans who work on the same category
              $category = $productInfos["category"];
              if($category != "Nonee"){
                addParagraph(translate("Artisans who work on the same category"));
                $previewArtisansWhoWorkOnTheSameCategory = obtainPreviewArtisansWhoWorkOnTheSameCategory($category,$_SESSION["userId"],8);
                $firstArtisan = true;
                $needToEndCardGrid = false;
                foreach($previewArtisansWhoWorkOnTheSameCategory as &$singleArtisanPreview){
                  if(!isThisUserCollaboratingForTheProductionOfThisProduct($singleArtisanPreview["id"],$_GET["id"])){
                    if($firstArtisan){
                      startCardGrid();
                      $needToEndCardGrid = true;
                      $firstArtisan = false;
                    }
                    $fileImageToVisualize = genericUserImage;
                    if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                      $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
                    }
                    addACardFunctionToCallAfter("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of the same category of this artisan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisanWithThisCategory"],"insertInInsertedParticipant",$singleArtisanPreview["email"]);
                  }
                }
                if($needToEndCardGrid == true){
                  endCardGrid();
                } else {
                  addParagraph(translate("No result"));
                }
              }
              ?>
                <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedParticipant = document.getElementById('insertedParticipant');
    
                  function isValidEmail(email){
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return emailRegex.test(email);
                  }
    
                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedParticipant.value.trim() == ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the new participant") ?>");
                    } else if(!isValidEmail(insertedParticipant.value)){
                      e.preventDefault();
                      alert("<?= translate("The email address of this new participant is not valid") ?>");
                    }
                  }

                  //function to insert suggested artisans and designers
                  function insertInInsertedParticipant(parteceipantToInsert){
                    insertedParticipant.value = parteceipantToInsert;
                    $("#submit").unbind('click').click();
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
