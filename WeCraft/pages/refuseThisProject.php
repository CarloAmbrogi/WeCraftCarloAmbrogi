<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Refuse this project (if you are assigned to this project) by the id of the project
  //Only for not confirmed projects
  //In case you have claimed the project, also the project became unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for refusing this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Refuse project"),"");
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is assigned to this project, the project is not confirmed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        if(!$thisProjectIsConfirmed){
          if(isThisArtisanAssignedToThisProject($_SESSION["userId"],$insertedProjectId)){
            if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
              makeThisProjectUnclaimed($insertedProjectId);
            }
            removeThisArtisanFromThisProject($_SESSION["userId"],$insertedProjectId);
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("You are not assigned to this project"));
          }
        } else {
          addParagraph(translate("This project is already confirmed"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Refuse project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is assigned to this project and that the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        if(!$thisProjectIsConfirmed){
          if(isThisArtisanAssignedToThisProject($_SESSION["userId"],$_GET["id"])){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Refuse this project
            addTitle(translate("Refuse this project"));
            //Form to refuse this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Refuse project"));
            //End main content of this page
          } else {
            addParagraph(translate("You are not assigned to this project"));
          }
        } else {
          addParagraph(translate("This project is already confirmed"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Refuse project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
