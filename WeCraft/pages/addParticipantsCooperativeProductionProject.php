<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for adding a participant (artisan or designer) as collaborator for the production of this project
  //(get param id is te id of the project related to this collaboration)
  //You need to be the artisan who has claimed this project
  //You can see this page only if the collaborating production for this project is active and if the project is not completed
  //Suggestions for the designer of the project and ex candidate artisans available
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative production"),"cookieBack");
      //Receive post request to add a new participant to the cooperative production for this project
      $insertedProjectId = $_POST['insertedProjectId'];
      $insertedParticipant = trim($_POST['insertedParticipant']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProjectExists($insertedProjectId)){
        addParagraph(translate("This project doesnt exists"));
      } else if(!isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$insertedProjectId)){
        addParagraph(translate("You are not a collaborator for the production of this project"));
      } else if($insertedParticipant == ""){
        addParagraph(translate("You have missed to insert the new participant"));
      } else if(strlen($insertedParticipant) > 49){
        addParagraph(translate("The email address of the new participant is too long"));
      } else if(!isValidEmail($insertedParticipant)){
        addParagraph(translate("The email address of this new participant is not valid"));
      } else {
        //Check to be the artisan who has claimed this project and that the project is not completed
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
          if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
            addParagraph(translate("The project is already ready"));
          } else {
            //Check that the user you are going to add exists and it is an artisan or a designer
            //and is not already collaborating for the production of this project
            if(doesThisUserGivenEmailExists($insertedParticipant)){
              $userToAdd = idUserWithThisEmail($insertedParticipant);
              $kindUserToAdd = getKindOfThisAccount($userToAdd);
              if($kindUserToAdd == "Artisan" || $kindUserToAdd == "Designer"){
                if(!isThisUserCollaboratingForTheProductionOfThisProject($userToAdd,$insertedProjectId)){
                  //The new user is added to the collaboration of this project
                  startCooperatingProductionForThisProject($userToAdd,$insertedProjectId);
                  //Send a notification to the user
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$userToAdd,"You have been added to the collaboration for the production of this project","project",$insertedProjectId);
                  //Show that the user has been added
                  $userInfos = obtainUserInfos($userToAdd);
                  addParagraph(translate("The user")." ".$userInfos["name"]." ".$userInfos["surname"]." (".$userInfos["email"].") ".translate("has been added"));
                } else {
                  addParagraph(translate("The user is already collaborating for the production of this personalized product"));
                }
              } else {
                addParagraph(translate("The user you are going to add is not an artisan or a designer"));
              }
            } else {
              addParagraph(translate("The user you are going to add doesnt exists"));
            }
          }
          addButtonLink(translate("Add another user"),"./addParticipantsCooperativeProductionProject.php?id=".urlencode($insertedProjectId));
        } else {
          addParagraph(translate("You are not the artisan who has claimed this project"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProjectExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$_GET["id"])){
            //Check to be the artisan who has claimed this project and that the project is not completed
            $projectInfos = obtainProjectInfos($_GET["id"]);
            if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
              if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
                upperPartOfThePage(translate("Error"),"");
                addParagraph(translate("The project is already ready"));
              } else {
                addScriptAddThisPageToChronology();
                upperPartOfThePage(translate("Cooperative production"),"cookieBack");
                //Real content of the page
                addParagraph(translate("Project").": ".$projectInfos["name"]);
                //Form to insert data to add the new participant
                startForm1();
                startForm2($_SERVER['PHP_SELF']);
                addShortTextField(translate("Insert the email address of the new participant to add for the collaboration for the production of this personalized product"),"insertedParticipant",49);
                addHiddenField("insertedProjectId",$_GET["id"]);
                endForm(translate("Submit"));
                //Suggested designer
                if(!isThisUserCollaboratingForTheProductionOfThisProject($projectInfos["designer"],$_GET["id"])){
                  addParagraph(translate("This is the designer who has created this project if you want to quickly add him").":");
                  $designerUserInfos = obtainUserInfos($projectInfos["designer"]);
                  $fileImageToVisualize = genericUserImage;
                  if(isset($designerUserInfos['icon']) && ($designerUserInfos['icon'] != null)){
                    $fileImageToVisualize = blobToFile($designerUserInfos["iconExtension"],$designerUserInfos['icon']);
                  }
                  addACardFunctionToCallAfter("./designer.php?id=".urlencode($designerUserInfos["id"]),$fileImageToVisualize,htmlentities($designerUserInfos["name"]." ".$designerUserInfos["surname"]),translate("Designer"),"","insertInInsertedParticipant",$designerUserInfos["email"]);
                }
                //Suggested artisans
                $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($_GET["id"]);
                $firstArtisan = true;
                $needToEndCardGrid = false;
                foreach($previewArtisansToWitchIsAssignedThisProject as &$singleArtisanPreview){
                  if(!isThisUserCollaboratingForTheProductionOfThisProject($singleArtisanPreview["id"],$_GET["id"])){
                    if($firstArtisan){
                      addParagraph(translate("These are the other artisans candidate to this project if you want to quickly add them").":");
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
                }
                //Js for the form
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
              }
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You are not the artisan who has claimed this project"));
            }
          } else {
            upperPartOfThePage(translate("Error"),"");
            addParagraph(translate("You are not a collaborator for the production of this project"));
          }
        } else {
          upperPartOfThePage(translate("Error"),"");
          addParagraph(translate("This project doesnt exists"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        //You have missed to specify the get param id of the project
        addParagraph(translate("You have missed to specify the get param id of the project"));
      }
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
