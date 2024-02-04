<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Present artisans to this project (if you are the designer of this project) by the id of the project
  //Only for not claimed projects
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for presenting an artisan to this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Assign artisans"),"./project.php?id=".urlencode($insertedProjectId));
    $insertedCandidate = trim($_POST['insertedCandidate']);
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedCandidate == ""){
      addParagraph(translate("You have missed to insert the new candidate"));
    } else if(strlen($insertedCandidate) > 49){
      addParagraph(translate("The email address of the new candidate is too long"));
    } else if(!isValidEmail($insertedCandidate)){
      addParagraph(translate("The email address of this new candidate is not valid"));
    } else {
      //Check that this project exists, the user is who has created the project, the project is not claimed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $isTheProjectClaimed = false;
          if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
            $isTheProjectClaimed = true;
          }
          if(!$isTheProjectClaimed){
            //Check that the user you are going to add exists and it is an artisan
            //and is not already a candidate for this project
            if(doesThisUserGivenEmailExists($insertedCandidate)){
              $userToAdd = idUserWithThisEmail($insertedCandidate);
              $kindUserToAdd = getKindOfThisAccount($userToAdd);
              if($kindUserToAdd == "Artisan"){
                if(!isThisArtisanAssignedToThisProject($userToAdd,$insertedProjectId)){
                  //The new artisan is added as candidate for this project
                  assignArtisanToThisProject($userToAdd,$insertedProjectId);
                  $userInfos = obtainUserInfos($userToAdd);
                  addParagraph(translate("The user")." ".$userInfos["name"]." ".$userInfos["surname"]." (".$userInfos["email"].") ".translate("has been added"));
                  //Send notification to the customer and to the new artisan
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"The designer has presented a new artisan to this project","project",$insertedProjectId);
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$userToAdd,"The designer has presented you to this project","project",$insertedProjectId);
                } else {
                  addParagraph(translate("The user is already assigned to this project"));
                }
              } else {
                addParagraph(translate("The user you are going to add is not an artisan"));
              }
            } else {
              addParagraph(translate("The user you are going to add doesnt exists"));
            }
            addButtonLink(translate("Add another user"),"./assignArtisansToThisProject.php?id=".urlencode($insertedProjectId));
          } else {
            addParagraph(translate("This project is already claimed"));
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
      upperPartOfThePage(translate("Assign artisans"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project and that the project is not claimed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $isTheProjectClaimed = false;
          if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
            $isTheProjectClaimed = true;
          }
          if(!$isTheProjectClaimed){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Present artisans to this project
            addTitle(translate("Present artisans to this project"));
            //Form to insert data to present a new artisan candidate for this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addShortTextField(translate("Insert the email address of the new candidate to which present this project"),"insertedCandidate",49);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedCandidate = document.getElementById('insertedCandidate');
    
                  function isValidEmail(email){
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return emailRegex.test(email);
                  }
    
                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedCandidate.value.trim() == ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the new candidate") ?>");
                    } else if(!isValidEmail(insertedCandidate.value)){
                      e.preventDefault();
                      alert("<?= translate("The email address of this new candidate is not valid") ?>");
                    }
                  }
                </script>
            <?php
            //End main content of this page
          } else {
            addParagraph(translate("This project is already claimed"));
          }
        } else {
          addParagraph(translate("You cant modify this project"));
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
