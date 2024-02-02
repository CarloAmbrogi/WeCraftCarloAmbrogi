<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for leaving the collaboration for the design of this project
  //(get param id is te id of the project related to this collaboration)
  //You can see this page only if the collaborating design for this project is active and if the project is not completed
  //You can't see this page if you are the artisan who has claimed this project
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Delete cooperation"),"");
      //Receive post request to delete the collaboration for the design of this project
      $insertedProjectId = $_POST['insertedProjectId'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProjectExists($insertedProjectId)){
        addParagraph(translate("This project doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
        addParagraph(translate("You are not a collaborator for the design of this project"));
      } else {
        //Check to not be the artisan who has claimed this project and check that the project is not completed
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["claimedByThisArtisan"] != $_SESSION["userId"]){
          if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
            addParagraph(translate("The project is already ready"));
          } else {
            //leave the collaboration for the design of this project
            removeParticipantCooperatingDesignForThisProject($_SESSION["userId"],$insertedProjectId);
            addParagraph(translate("Done"));
          }
        } else {
          addParagraph(translate("You cant leave the collaboration beacause you are the artisan who has claimed this project"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProjectExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"])){
            //Check to not be the artisan who has claimed this project and check that the project is not completed
            $projectInfos = obtainProjectInfos($_GET["id"]);
            if($projectInfos["claimedByThisArtisan"] != $_SESSION["userId"]){
              if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
                upperPartOfThePage(translate("Error"),"");
                addParagraph(translate("The project is already ready"));
              } else {
                addScriptAddThisPageToCronology();
                upperPartOfThePage(translate("Leave cooperation"),"cookieBack");
                //Real content of the page
                addParagraph(translate("Project").": ".$projectInfos["name"]);
                addParagraph(translate("Leave this cooperation for the design for this project")."?");
                //Form to insert data to leave this cooperation for the design for this project
                startForm1();
                startForm2($_SERVER['PHP_SELF']);
                addHiddenField("insertedProjectId",$_GET["id"]);
                endForm(translate("Leave cooperation"));
                //End main content of this page
              }
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You cant leave the collaboration beacause you are the owner"));
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
