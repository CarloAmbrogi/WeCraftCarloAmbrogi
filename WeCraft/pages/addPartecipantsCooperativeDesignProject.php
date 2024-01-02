<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for adding a partecipant (artisan or designer) as collaborator for the design of this project
  //(get param id is te id of the project related to this collaboration)
  //You need to be the artisan who has claimed this project
  //You can see this page only if the collaborating design for this project is active and if the project is not completed
  //Suggestions for the designer of the project and ex candidate artisans available
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative design"),"cookieBack");
      //Receive post request to add a new partecipant to the cooperative design for this project
      $insertedProjectId = $_POST['insertedProjectId'];
      $insertedPartecipant = trim($_POST['insertedPartecipant']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProjectExists($insertedProjectId)){
        addParagraph(translate("This project doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
        addParagraph(translate("You are not a collaborator for the design of this project"));
      } else if($insertedPartecipant == ""){
        addParagraph(translate("You have missed to insert the new partecipant"));
      } else if(strlen($insertedPartecipant) > 49){
        addParagraph(translate("The email address of the new partecipant is too long"));
      } else if(!isValidEmail($insertedPartecipant)){
        addParagraph(translate("The email address of this new partecipant is not valid"));
      } else {
        //Check to be the artisan who has claimed this project and that the project is not completed
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
          if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
            addParagraph(translate("The project is already ready"));
          } else {
            //Check that the user you are going to add exists and it is an artisan or a designer
            //and is not already collaborating for the design of this project
            if(doesThisUserGivenEmailExists($insertedPartecipant)){
              $userToAdd = idUserWithThisEmail($insertedPartecipant);
              $kindUserToAdd = getKindOfThisAccount($userToAdd);
              if($kindUserToAdd == "Artisan" || $kindUserToAdd == "Designer"){
                if(!isThisUserCollaboratingForTheDesignOfThisProject($userToAdd,$insertedProjectId)){
                  //The new user is added to the collaboration of this project
                  startCooperatingDesignForThisProject($userToAdd,$insertedProjectId);
                  $userInfos = obtainUserInfos($userToAdd);
                  addParagraph(translate("The user")." ".$userInfos["name"]." ".$userInfos["surname"]." (".$userInfos["email"].") ".translate("has been added"));
                } else {
                  addParagraph(translate("The user is already collaborating for the design of this personalized product"));
                }
              } else {
                addParagraph(translate("The user you are going to add is not an artisan or a designer"));
              }
            } else {
              addParagraph(translate("The user you are going to add doesnt exists"));
            }
          }
          addButtonLink(translate("Add another user"),"./addPartecipantsCooperativeDesignProject.php?id=".urlencode($insertedProjectId));
        } else {
          addParagraph(translate("You are not the artisan who has claimed this project"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProjectExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"])){
            //Check to be the artisan who has claimed this project and that the project is not completed
            $projectInfos = obtainProjectInfos($_GET["id"]);
            if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
              if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
                upperPartOfThePage(translate("Error"),"");
                addParagraph(translate("The project is already ready"));
              } else {
                addScriptAddThisPageToCronology();
                upperPartOfThePage(translate("Cooperative design"),"cookieBack");
                //Real content of the page
                addParagraph(translate("Project").": ".$projectInfos["name"]);
                //Form to insert data to add the new partecipant
                startForm1();
                startForm2($_SERVER['PHP_SELF']);
                addShortTextField(translate("Insert the email address of the new partecipant to add for the collaboration for the design of this personalized product"),"insertedPartecipant",49);
                addHiddenField("insertedProjectId",$_GET["id"]);
                endForm(translate("Submit"));
                //Suggested designer
                if(!isThisUserCollaboratingForTheDesignOfThisProject($projectInfos["designer"],$_GET["id"])){
                  addParagraph(translate("This is the designer who has created this project if you want to quickly add him").":");
                  $designerUserInfos = obtainUserInfos($projectInfos["designer"]);
                  $fileImageToVisualize = genericUserImage;
                  if(isset($designerUserInfos['icon']) && ($designerUserInfos['icon'] != null)){
                    $fileImageToVisualize = blobToFile($designerUserInfos["iconExtension"],$designerUserInfos['icon']);
                  }
                  addACardFunctionToCallAfter("./designer.php?id=".urlencode($designerUserInfos["id"]),$fileImageToVisualize,htmlentities($designerUserInfos["name"]." ".$designerUserInfos["surname"]),translate("Designer"),"","insertInInsertedPartecipant",$designerUserInfos["email"]);
                }
                //Suggested artisans
                $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($_GET["id"]);
                $firstArtisan = true;
                $needToEndCardGrid = false;
                foreach($previewArtisansToWitchIsAssignedThisProject as &$singleArtisanPreview){
                  if(!isThisUserCollaboratingForTheDesignOfThisProject($singleArtisanPreview["id"],$_GET["id"])){
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
                    addACardFunctionToCallAfter("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"],"insertInInsertedPartecipant",$singleArtisanPreview["email"]);
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
                    const insertedPartecipant = document.getElementById('insertedPartecipant');
      
                    function isValidEmail(email){
                      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                      return emailRegex.test(email);
                    }
      
                    //prevent sending form with errors
                    form.onsubmit = function(e){
                      if(insertedPartecipant.value.trim() == ""){
                        e.preventDefault();
                        alert("<?= translate("You have missed to insert the new partecipant") ?>");
                      } else if(!isValidEmail(insertedPartecipant.value)){
                        e.preventDefault();
                        alert("<?= translate("The email address of this new partecipant is not valid") ?>");
                      }
                    }

                    //function to insert suggested artisans and designers
                    function insertInInsertedPartecipant(parteceipantToInsert){
                      insertedPartecipant.value = parteceipantToInsert;
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
            addParagraph(translate("You are not a collaborator for the design of this project"));
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
