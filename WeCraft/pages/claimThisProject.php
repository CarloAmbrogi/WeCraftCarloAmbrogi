<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Claim this project (if you are assigned to this project) by the id of the project
  //Only for not claimed projects
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for claiming this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Claim project"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is assigned to this project, the project is not claimed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
        }
        if(!$isTheProjectClaimed){
          if(isThisArtisanAssignedToThisProject($_SESSION["userId"],$insertedProjectId)){
            //Claim this project
            claimThisProject($_SESSION["userId"],$insertedProjectId);
            //Send notification to the customer and to the designer
            sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"This artisan has claimed this project","project",$insertedProjectId);
            sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["designer"],"This artisan has claimed this project","project",$insertedProjectId);
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("You are not assigned to this project"));
          }
        } else {
          addParagraph(translate("This project has been claimed"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Claim project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is assigned to this project and that the project is not claimed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
        }
        if(!$isTheProjectClaimed){
          if(isThisArtisanAssignedToThisProject($_SESSION["userId"],$_GET["id"])){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Claim this project
            addTitle(translate("Claim this project"));
            //Form to claim this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Claim project"));
            //End main content of this page
          } else {
            addParagraph(translate("You are not assigned to this project"));
          }
        } else {
          addParagraph(translate("This project has been claimed"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Claim project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
