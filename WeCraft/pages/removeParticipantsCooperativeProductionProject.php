<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for removing participants (artisan or designer) as collaborators for the production of this project
  //(get param id is te id of the project related to this collaboration)
  //You need to be the artisan who has claimed this project
  //You can see this page only if the collaborating production for this project is active
  //You cant remove yourself
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative production"),"cookieBack");
      //Receive post request to remove participants to the cooperative production for this project
      $insertedProjectId = $_POST['insertedProjectId'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProjectExists($insertedProjectId)){
        addParagraph(translate("This project doesnt exists"));
      } else if(!isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$insertedProjectId)){
        addParagraph(translate("You are not a collaborator for the production of this project"));
      } else {
        //Check to be the artisan who has claimed this project
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["claimedByThisArtisan"]){
          //Remove participants
          $previewArtisansCollaboratorsOfThisProject = obtainPreviewArtisansCollaboratorsOfThisProject($insertedProjectId);
          $previewDesignersCollaboratorsOfThisProject = obtainPreviewDesignersCollaboratorsOfThisProject($insertedProjectId);
          $removedAtLeastAParticipant = false;
          foreach ($previewArtisansCollaboratorsOfThisProject as &$singleArtisanPreview){
            $idOfThisParticipant = $singleArtisanPreview["id"];
            $postOfThisParticipant = $_POST['participant'.$idOfThisParticipant];
            if($postOfThisParticipant == true){
              removeParticipantCooperatingProductionForThisProject($idOfThisParticipant,$insertedProjectId);
              $removedAtLeastAParticipant = true;
              //Send a notification to the user
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$idOfThisParticipant,"You have been removed to the collaboration for the production of this project","project",$insertedProjectId);
            }
          }
          foreach ($previewDesignersCollaboratorsOfThisProject as &$singleDesignerPreview){
            $idOfThisParticipant = $singleDesignerPreview["id"];
            $postOfThisParticipant = $_POST['participant'.$idOfThisParticipant];
            if($postOfThisParticipant == true){
              removeParticipantCooperatingProductionForThisProject($idOfThisParticipant,$insertedProjectId);
              $removedAtLeastAParticipant = true;
              //Send a notification to the user
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$idOfThisParticipant,"You have been removed to the collaboration for the production of this project","project",$insertedProjectId);
            }
          }
          if($removedAtLeastAParticipant){
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("No participant has been removed"));
          }
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
            //Check you are the artisan who has claimed this project
            $projectInfos = obtainProjectInfos($_GET["id"]);
            if($_SESSION["userId"] == $projectInfos["claimedByThisArtisan"]){
              addScriptAddThisPageToChronology();
              upperPartOfThePage(translate("Cooperative production"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Project").": ".$projectInfos["name"]);
              //Title Remove participants
              addTitle(translate("Remove participants"));
              $numberCollaboratorsForThisProject = obtainNumberCollaboratorsForThisProject($_GET["id"]);
              if($numberCollaboratorsForThisProject >= 2){
                //Form to insert data to remove participants
                startForm1();
                addParagraphInAForm(translate("Select the participants to remove"));
                startForm2($_SERVER['PHP_SELF']);
                $previewArtisansCollaboratorsOfThisProject = obtainPreviewArtisansCollaboratorsOfThisProject($_GET["id"]);
                $previewDesignersCollaboratorsOfThisProject = obtainPreviewDesignersCollaboratorsOfThisProject($_GET["id"]);
                foreach ($previewArtisansCollaboratorsOfThisProject as &$singleArtisanPreview){
                  ?>
                    <ul class="list-group">
                      <li class="list-group-item">
                        <div for="participant<?= $singleArtisanPreview["id"] ?>" class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="participant<?= $singleArtisanPreview["id"] ?>" name="participant<?= $singleArtisanPreview["id"] ?>">
                          <?php addParagraph($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]) ?>
                        </div>
                      </li>
                    </ul>
                  <?php
                }
                foreach ($previewDesignersCollaboratorsOfThisProject as &$singleDesignerPreview){
                  ?>
                    <ul class="list-group">
                      <li class="list-group-item">
                        <div for="participant<?= $singleDesignerPreview["id"] ?>" class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="participant<?= $singleDesignerPreview["id"] ?>" name="participant<?= $singleDesignerPreview["id"] ?>">
                          <?php addParagraph($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]) ?>
                        </div>
                      </li>
                    </ul>
                  <?php
                }
                addHiddenField("insertedProjectId",$_GET["id"]);
                endForm(translate("Submit"));
              } else {
                addParagraph(translate("There arent participants to remove"));
              }
              //End main content of this page
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
