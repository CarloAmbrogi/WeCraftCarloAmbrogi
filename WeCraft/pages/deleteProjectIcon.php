<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Delete project icon of a project (if you are the designer of this project) by the id of the project
  //Only for not confirmed projects which will become unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for deleting the icon of this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Delete project icon"),"./project.php?id=".urlencode($insertedProjectId));
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
          if(isset($projectInfos["confirmedByTheCustomer"]) and $projectInfos["confirmedByTheCustomer"] != null){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Delete project icon and make the project unclaimed
            deleteIconOfAProject($insertedProjectId);
            //Send notification to the customer and to the assigned artisans
            //AAAAAAA
            addParagraph(translate("Done"));
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
      upperPartOfThePage(translate("Delete project icon"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project and that the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if(isset($projectInfos["confirmedByTheCustomer"]) and $projectInfos["confirmedByTheCustomer"] != null){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Delete project icon
            addTitle(translate("Delete project icon"));
            //Form to insert data to delete the icon of this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Delete icon"));
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
      upperPartOfThePage(translate("Delete project icon"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
