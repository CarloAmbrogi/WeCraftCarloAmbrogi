<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Start cooperative design for this project
  //If you have claimed this project and the project is confirmed you can start the cooperative design (and create a group) for this project
  //Not available if the project is completed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for starting a cooperative design for this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Start cooperative design"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user has claimed this project, the project is confirmed, the project is not completed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
          if($thisProjectIsConfirmed){
            if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
              addParagraph(translate("The project is already ready"));
            } else {
              //Check that the collaboration for the cooperative design in not already setted
              $numberCollaboratorsForThisProject = obtainNumberCollaboratorsForThisProject($insertedProjectId);
              if($numberCollaboratorsForThisProject == 0){
                //Start cooperative design for this project
                startCooperatingDesignForThisProject($_SESSION["userId"],$insertedProjectId);
                addSheetCooperatingDesignForThisProject($insertedProjectId);
                addParagraph(translate("The collaboration for the design of this customized product has started"));
                addButtonLink(translate("See collaboration"),"./cooperativeDesignProject.php?id=".urlencode($insertedProjectId));
                addParagraph(translate("From the page of the collaboration youll be able to add partecipants to this collaboration"));
              } else {
                addParagraph(translate("The cooperative design for this customized product is already active"));
                addButtonLink(translate("See collaboration"),"./cooperativeDesignProject.php?id=".urlencode($insertedProjectId));
              }
            }
          } else {
            addParagraph(translate("This project is not confirmed"));
          }
        } else {
          addParagraph(translate("You are not the artisan who has claimed this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Start cooperative design"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user has claimed this project, the project is confirmed, the project is not completed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
          if($thisProjectIsConfirmed){
            if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
              addParagraph(translate("The project is already ready"));
            } else {
              //Check that the collaboration for the cooperative design in not already setted
              $numberCollaboratorsForThisProject = obtainNumberCollaboratorsForThisProject($_GET["id"]);
              if($numberCollaboratorsForThisProject == 0){
                //Content of this page
                addParagraph(translate("Project").": ".$projectInfos["name"]);
                //Title Start cooperative design
                addTitle(translate("Start cooperative design"));
                addParagraph(translate("Start cooperative design for this customized product")."?");
                //Form to insert data to start the cooperative design for this product
                startForm1();
                startForm2($_SERVER['PHP_SELF']);
                addHiddenField("insertedProjectId",$_GET["id"]);
                endForm(translate("Confirm"));
                //End main content of this page
              } else {
                addParagraph(translate("The cooperative design for this customized product is already active"));
                addButtonLink(translate("See collaboration"),"./cooperativeDesignProject.php?id=".urlencode($_GET["id"]));
              }
            }
          } else {
            addParagraph(translate("This project is not confirmed"));
          }
        } else {
          addParagraph(translate("You are not the artisan who has claimed this project"));
        }

      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Start cooperative design"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
