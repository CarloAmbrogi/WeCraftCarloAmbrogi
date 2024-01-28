<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Set this project private (if you are the designer or the customer of this project) by the id of the project
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for setting this project private
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is who has created the project or the customer for who is this project assigned, the project is not already private
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($projectInfos["isPublic"] == 0){
          addParagraph(translate("The project is already private"));
        } else {
          if($_SESSION["userId"] == $projectInfos["designer"] || $_SESSION["userId"] == $projectInfos["customer"]){
            //make the project public
            makeThisProjectPrivate($insertedProjectId);
            //Send notification to the customer / designer
            if($_SESSION["userId"] == $projectInfos["designer"]){
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"I have set this project private","project",$insertedProjectId);
            }
            if($_SESSION["userId"] == $projectInfos["customer"]){
              sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["designer"],"I have set this project private","project",$insertedProjectId);
            }
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("You cant modify this project"));
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
        //Verify that the user is who has created the project or the customer for who is the project and that the project is not already private
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($projectInfos["isPublic"] == 0){
          addParagraph(translate("The project is already private"));
        } else {
          if($_SESSION["userId"] == $projectInfos["designer"] || $_SESSION["userId"] == $projectInfos["customer"]){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Set this project private
            addTitle(translate("Set this project private")."?");
            //Form to remove images
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Set this project private"));
            //End main content of this page
          } else {
            addParagraph(translate("You cant modify this project"));
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
