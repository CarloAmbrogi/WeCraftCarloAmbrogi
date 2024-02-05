<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Annunce that this project is completed ready (if you are the artisan of this project) by the id of the project
  //Only for confirmed projects
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for annuncing that this project is ready
    $insertedProjectId = $_POST['insertedProjectId'];
    $insertedFeedback = "";
    if(isset($_POST['insertedFeedback'])){
      $insertedFeedback = $_POST['insertedFeedback'];
    }
    upperPartOfThePage(translate("Annunce project ready"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedFeedback == "" && isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
      addParagraph(translate("You have missed to insert the feedback"));
    } else if(strlen($insertedFeedback) > 2046 && isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
      addParagraph(translate("The feedback is too long"));
    } else {
      //Check that this project exists, the user is the artisan of this project, the project is confirmed, the project is not yet ready
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        $thisProjectIsReady = false;
        if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
          $thisProjectIsReady = true;
        }
        if($_SESSION["userId"] == $projectInfos["claimedByThisArtisan"]){
          if($thisProjectIsConfirmed){
            if(!$thisProjectIsReady){
              setReadyThisProject($insertedProjectId);
              //Send a notification to the customer and also to the designer of this project
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"The personalized product is ready","project",$insertedProjectId);
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["designer"],"The personalized product is ready","project",$insertedProjectId);
              //In case of collaboration
              if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
                //Save the feedback of the claimer of the project
                saveFeedbackCollaboration($_SESSION["userId"], "claimer", $insertedProjectId, "project", $insertedFeedback);
                //Send a notification to all the other partecipants to write a feedback about how has gone the collaboration
                $previewArtisansCollaboratorsOfThisProject = obtainPreviewArtisansCollaboratorsOfThisProject($insertedProjectId);
                $previewDesignersCollaboratorsOfThisProject = obtainPreviewDesignersCollaboratorsOfThisProject($insertedProjectId);
                foreach($previewArtisansCollaboratorsOfThisProject as &$singleArtisanPreview){
                  $toWho = $singleArtisanPreview["id"];
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$toWho,"The collaboration for this project has terminated please send now a feedback about how has gone the collaboration to improve WeCraft","feedbackCollProj",$insertedProjectId);
                }
                foreach($previewDesignersCollaboratorsOfThisProject as &$singleDesignerPreview){
                  $toWho = $singleDesignerPreview["id"];
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$toWho,"The collaboration for this project has terminated please send now a feedback about how has gone the collaboration to improve WeCraft","feedbackCollProj",$insertedProjectId);
                }
              }
              addParagraph(translate("Done"));
            } else {
              addParagraph(translate("The project is already ready"));
            }
          } else {
            addParagraph(translate("This project is not confirmed"));
          }
        } else {
          addParagraph(translate("You arent the customer for which is this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Annunce project ready"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is the artisan of this project, the project is confirmed, the project is not yet ready
        $projectInfos = obtainProjectInfos($_GET["id"]);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        $thisProjectIsReady = false;
        if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
          $thisProjectIsReady = true;
        }
        if($_SESSION["userId"] == $projectInfos["claimedByThisArtisan"]){
          if($thisProjectIsConfirmed){
            if(!$thisProjectIsReady){
              //Content of this page
              addParagraph(translate("Project").": ".$projectInfos["name"]);
              //Title Annunce that this personalized item is ready
              addTitle(translate("Annunce that this personalized item is ready"));
              if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"])){
                addParagraph(translate("Provide also a feedback about how has gone this collaboration"));
              }
              //Form to annunce that this project is completed ready
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"])){
                addLongTextField(translate("Feedback"),"insertedFeedback",2046);
              }
              addHiddenField("insertedProjectId",$_GET["id"]);
              endForm(translate("Confirm project"));
              if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"])){
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
              }
              //End main content of this page
            } else {
              addParagraph(translate("The project is already ready"));
            }
          } else {
            addParagraph(translate("This project is not confirmed"));
          }
        } else {
          addParagraph(translate("You arent the customer for which is this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Annunce project ready"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
