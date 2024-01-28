<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Set this project public (if you are the designer of this project) by the id of the project
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for setting this project public
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is who has created the project, the project is not already public
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["isPublic"] == 1){
          addParagraph(translate("The project is already public"));
        } else {
          if($_SESSION["userId"] == $projectInfos["designer"]){
            //make the project public
            makeThisProjectPublic($insertedProjectId);
            //Send notification to the customer
            sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"I have set this project public","project",$insertedProjectId);
            addParagraph(translate("Done"));
          } else {
            if($_SESSION["userId"] == $projectInfos["customer"]){
              addParagraph(translate("The designer can set the project public"));
            } else {
              addParagraph(translate("You cant modify this project"));
            }
          }
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
        //Verify that the user is who has created the project and that the project is not already public
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($projectInfos["isPublic"] == 1){
          addParagraph(translate("The project is already public"));
        } else {
          if($_SESSION["userId"] == $projectInfos["designer"]){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Set this project public
            addTitle(translate("Set this project public")."?");
            //Form to remove images
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Set this project public"));
            //End main content of this page
          } else {
            if($_SESSION["userId"] == $projectInfos["customer"]){
              addParagraph(translate("The designer can set the project public"));
            } else {
              addParagraph(translate("You cant modify this project"));
            }
          }
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
