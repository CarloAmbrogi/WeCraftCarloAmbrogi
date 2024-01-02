<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Confirm this project (if you are the customer for which is this project) by the id of the project
  //Only for not confirmed but claimed projects
  //In this moment you also pay and send the order
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for confirming this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Confirm project"),"./project.php?id=".urlencode($insertedProjectId));
    $insertedAddress = trim($_POST['insertedAddress']);
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedAddress == ""){
      addParagraph(translate("You have missed to insert the address"));
    } else if(strlen($insertedAddress) > 49){
      addParagraph(translate("The address is too long"));
    } else {
      //Check that this project exists, the user is the customer to which is this project, the project is not confirmed, the project is claimed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
        }
        if($_SESSION["userId"] == $projectInfos["customer"]){
          if(!$thisProjectIsConfirmed){
            if($isTheProjectClaimed){
              confirmThisProject($insertedAddress,$insertedProjectId);
              //Send a notification to the artisan who has calimed the project and also to the designer of this project
              //AAAAAAAA
              addParagraph(translate("Done"));
            } else {
              addParagraph(translate("The project is not claimed by an artisan"));
            }
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You arent the customer for which is this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Confirm project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is the customer to which is this project, the project is not confirmed, the project is claimed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
        }
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
        }
        if($_SESSION["userId"] == $projectInfos["customer"]){
          if(!$thisProjectIsConfirmed){
            if($isTheProjectClaimed){
              //Content of this page
              addParagraph(translate("Project").": ".$projectInfos["name"]);
              //Title Confirm this project
              addTitle(translate("Confirm this project"));
              //Form to confirm this project and send the order
              $artisanUserInfos = obtainUserInfos($projectInfos["claimedByThisArtisan"]);
              $artisanArtisanInfos = obtainArtisanInfos($projectInfos["claimedByThisArtisan"]);
              addParagraph(translate("Artisan").": ".$artisanUserInfos["name"]." ".$artisanUserInfos["surname"]." (".$artisanArtisanInfos["shopName"].") (".$artisanUserInfos["email"].")");
              addParagraph(translate("Price").": ".floatToPrice($projectInfos["price"]));
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addShortTextField(translate("Address"),"insertedAddress",49);
              addHiddenField("insertedProjectId",$_GET["id"]);
              endForm(translate("Pay")." ".floatToPrice($projectInfos["price"])." ".translate("and send order"));
              ?>
                <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedAddress = document.getElementById('insertedAddress');

                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedAddress.value.trim() == ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the address") ?>");
                    }
                  }
                </script>
              <?php
              //End main content of this page
            } else {
              addParagraph(translate("The project is not claimed by an artisan"));
            }
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You arent the customer for which is this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Confirm project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
