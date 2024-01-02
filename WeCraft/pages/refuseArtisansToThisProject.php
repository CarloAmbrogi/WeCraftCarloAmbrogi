<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Refuse artisans to this project (if you are the designer of this project or the customer for which is this project) by the id of the project
  //Only for not confirmed projects. In case the artisan you refuse is the artisan who has claimed the project, the project become unclaimed.
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for refusing artisans to this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Refuse artisans"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is who has created the project or the customer for which is the project, the project is not confirmed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["designer"] || $_SESSION["userId"] == $projectInfos["customer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Now check if this project is assigned to at least an artisan (else it's not possible to refuse artisans)
            $numberArtisansAssignedThisProject = numberArtisansAssignedThisProject($insertedProjectId);
            if($numberArtisansAssignedThisProject == 0){
              addParagraph(translate("There isnt any artisan assigned to this project"));
            } else {
              //Remove artisans from this project
              $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($insertedProjectId);
              $refusedAtLeastAnArtisan = false;
              for($i=0;$i<$numberArtisansAssignedThisProject;$i++){
                $idOfThisArtisan = $previewArtisansToWitchIsAssignedThisProject[$i]["id"];
                $postOfThisArtisan = $_POST['artisan'.$idOfThisArtisan];
                if($postOfThisArtisan == true){
                  removeThisArtisanFromThisProject($idOfThisArtisan,$insertedProjectId);
                  if($projectInfos["claimedByThisArtisan"] == $idOfThisArtisan){
                    makeThisProjectUnclaimed($insertedProjectId);
                  }
                  $refusedAtLeastAnArtisan = true;
                }
              }
              if($refusedAtLeastAnArtisan){
                addParagraph(translate("Done"));
              } else {
                addParagraph(translate("No artisan has been refused"));
              }
            }
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You cant modify this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Refuse artisans"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project or the customer for which is the project, the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"] || $_SESSION["userId"] == $projectInfos["customer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Assign artisans to this project
            addTitle(translate("Refuse artisans to this project"));
            //Now check if this project is assigned to at least an artisan (else it's not possible to refuse artisans)
            $numberArtisansAssignedThisProject = numberArtisansAssignedThisProject($_GET["id"]);
            if($numberArtisansAssignedThisProject == 0){
              addParagraph(translate("There isnt any artisan assigned to this project"));
            } else {
              //Form to refuse artisans
              startForm1();
              addParagraphInAForm(translate("Select the artisans to refuse"));
              startForm2($_SERVER['PHP_SELF']);
              $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($_GET["id"]);
              for($i=0;$i<$numberArtisansAssignedThisProject;$i++){
                ?>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <div for="tag<?= $previewArtisansToWitchIsAssignedThisProject[$i]["id"] ?>" class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="artisan<?= $previewArtisansToWitchIsAssignedThisProject[$i]["id"] ?>" name="artisan<?= $previewArtisansToWitchIsAssignedThisProject[$i]["id"] ?>">
                        <?php addParagraph($previewArtisansToWitchIsAssignedThisProject[$i]['name']." ".$previewArtisansToWitchIsAssignedThisProject[$i]['surname']." (".$previewArtisansToWitchIsAssignedThisProject[$i]['shopName'].")"); ?>
                      </div>
                    </li>
                  </ul>
                <?php
              }
              addHiddenField("insertedProjectId",$_GET["id"]);
              endForm(translate("Submit"));
            }
            //End main content of this page
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You cant modify this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Refuse artisans"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
