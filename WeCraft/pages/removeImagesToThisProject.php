<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Remove images to this project (if you are the designer of this project) by the id of the project
  //Only for not confirmed projects which will become unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for adding images to this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is who has created the project, the project is not confirmed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Now check if this project has some images (else it's not possible to remove images)
            $numberOfImages = getNumberImagesOfThisProject($insertedProjectId);
            if ($numberOfImages == 0){
              addParagraph(translate("You have no images"));
            } else {
              //Remove images from this project
              $images = getImagesOfThisProject($insertedProjectId);
              $removedAtLeastAnImage = false;
              for($i=0;$i<$numberOfImages;$i++){
                $idOfThisImage = $images[$i]["id"];
                $postOfThisImage = $_POST['image'.$idOfThisImage];
                if($postOfThisImage == true){
                  removeThisImageToAProject($insertedProjectId,$idOfThisImage);
                  $removedAtLeastAnImage = true;
                }
              }
              if($removedAtLeastAnImage){
                //make the project unclaimed
                makeThisProjectUnclaimed($insertedProjectId);
                //Send notification to the customer and to the assigned artisans
                //AAAAAAA
                addParagraph(translate("Done"));
              } else {
                addParagraph(translate("No image has been removed"));
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
      upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project and that the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Remove images to this project
            addTitle(translate("Remove images to this project"));
            $numberOfImages = getNumberImagesOfThisProject($_GET["id"]);
            if($numberOfImages == 0){
              addParagraph(translate("You have no images"));
            } else {
              //Form to remove images
              startForm1();
              addParagraphInAForm(translate("Select the images to remove"));
              startForm2($_SERVER['PHP_SELF']);
              $images = getImagesOfThisProject($_GET["id"]);
              for($i=0;$i<$numberOfImages;$i++){
                ?>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <div for="image<?= $images[$i]["id"] ?>" class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="image<?= $images[$i]["id"] ?>" name="image<?= $images[$i]["id"] ?>">
                        <?php addImage(blobToFile($images[$i]["imgExtension"],$images[$i]['image']),"Image ".($i+1)); ?>
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
      upperPartOfThePage(translate("Edit project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
