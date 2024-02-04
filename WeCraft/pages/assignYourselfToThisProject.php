<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Present yourself to this project (you need to be an artisan) by the id of the project
  //Only for not claimed public projects and if you are not aleady assigned
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for presenting yourself to this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Assign artisans"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the project is public, you are not already assigned to the project, the project is not claimed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["isPublic"] == 1){
          if(!isThisArtisanAssignedToThisProject($_SESSION["userId"],$insertedProjectId)){
            $isTheProjectClaimed = false;
            if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
              $isTheProjectClaimed = true;
            }
            if(!$isTheProjectClaimed){
              //You are added as candidate for this project
              assignArtisanToThisProject($_SESSION["userId"],$insertedProjectId);
              //Send notification to the customer and to the designer
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"I have presented myself to this project","project",$insertedProjectId);
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["designer"],"I have presented myself to this project","project",$insertedProjectId);
              //Dome
              addParagraph(translate("Now you are assigned to this project and you may be suggested for cooperations"));
              addParagraph(translate("Moreover now youll be able to claim the project"));
            } else {
              addParagraph(translate("This project is already claimed"));
            }
          } else {
            addParagraph(translate("You are already presented to this project"));
          }
        } else {
          addParagraph(translate("This project is not public"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Assign artisans"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //the project is public, you are not already preseted to the project, the project is not claimed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($projectInfos["isPublic"] == 1){
          if(!isThisArtisanAssignedToThisProject($_SESSION["userId"],$_GET["id"])){
            $isTheProjectClaimed = false;
            if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
              $isTheProjectClaimed = true;
            }
            if(!$isTheProjectClaimed){
              //Content of this page
              addParagraph(translate("Project").": ".$projectInfos["name"]);
              //Title Present yourself to this project
              addTitle(translate("Present yourself to this project")."?");
              //Form to insert data to present yourself for this project
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addHiddenField("insertedProjectId",$_GET["id"]);
              endForm(translate("Present yourself to this project"));
              //End main content of this page
            } else {
              addParagraph(translate("This project is already claimed"));
            }
          } else {
            addParagraph(translate("You are already presented to this project"));
          }
        } else {
          addParagraph(translate("This project is not public"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Assign artisans"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
