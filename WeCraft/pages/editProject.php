<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit general info of a project (if you are the designer of this project) by the id of the project
  //Only for not confirmed projects which will become unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the general info of this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($insertedProjectId));
    $insertedName = trim($_POST['insertedName']);
    $insertedDescription = trim($_POST['insertedDescription']);
    $insertedPrice = $_POST['insertedPrice'];
    $insertedPercentageToDesigner = $_POST['insertedPercentageToDesigner'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedName == ""){
      addParagraph(translate("You have missed to insert the name"));
    } else if(strlen($insertedName) > 24){
      addParagraph(translate("The name is too long"));
    } else if($insertedDescription == ""){
      addParagraph(translate("You have missed to insert the description"));
    } else if(strlen($insertedDescription) > 2046){
      addParagraph(translate("The description is too long"));
    }  else if($insertedPrice == ""){
      addParagraph(translate("You have missed to insert the price"));
    } else if(strlen($insertedPrice) > 24){
      addParagraph(translate("The price is too long"));
    } else if(!isValidPrice($insertedPrice)){
      addParagraph(translate("The price is not in the format number plus dot plus two digits"));
    } else if($insertedPercentageToDesigner == ""){
      addParagraph(translate("You have missed to insert the percentage to the designer"));
    } else if(strlen($insertedPercentageToDesigner) > 5){
      addParagraph(translate("The inserted percentage to the designer is too long"));
    } else if(!isValidPercentage($insertedPercentageToDesigner)){
      addParagraph(translate("The inserted percentage to the designer is not valid"));
    } else if($insertedPercentageToDesigner < 0.0 || $insertedPercentageToDesigner > 100.0){
      addParagraph(translate("The percentage is not between z and h"));
    } else {
      //Check that this project exists, the user is who has created the project, the project is not confirmed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Edit general info of this project and make it unclaimed
            updateGeneralInfoOfAProject($insertedProjectId,$insertedName,$insertedDescription,$insertedPrice,$insertedPercentageToDesigner);
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
      upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project and that the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Content of this page
            //Title Edit project
            addTitle(translate("Edit project"));
            //Form to insert data edit project general info
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addShortTextField(translate("Name"),"insertedName",24);
            addLongTextField(translate("Description"),"insertedDescription",2046);
            addShortTextField(translate("Price"),"insertedPrice",24);
            addShortTextField(translate("Percentage to the designer"),"insertedPercentageToDesigner",5);
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const insertedName = document.getElementById('insertedName');
                const insertedDescription = document.getElementById('insertedDescription');
                const insertedPrice = document.getElementById('insertedPrice');
                const insertedPercentageToDesigner = document.getElementById('insertedPercentageToDesigner');

                //Load form fields starting values
                insertedName.value = "<?= $projectInfos["name"] ?>";
                insertedDescription.value = "<?= newlineForJs($projectInfos["description"]) ?>";
                insertedPrice.value = "<?= floatToPrice($projectInfos["price"]) ?>";
                insertedPercentageToDesigner.value = "<?= $projectInfos["percentageToDesigner"] ?>";
      
                function isValidPrice(price){
                  //The price shoud have at least an integer digit and exactly 2 digits after the floating point
                  const priceRegex = /^[0-9]+\.[0-9][0-9]$/;
                  return priceRegex.test(price);
                }

                function isValidFloatNumber(number){
                  const floatNumberRegex = /^[0-9]+\.?[0-9]*$/;
                  return floatNumberRegex.test(number);
                }

                function isValidQuantity(quantity){
                  const quantityRegex = /^[0-9]+$/;
                  return quantityRegex.test(quantity);
                }

                function isValidPercentage(percentage){
                  if(isValidFloatNumber(percentage)){
                    return true;
                  }
                  return isValidQuantity(percentage);
                }
      
                //prevent sending form with errors
                form.onsubmit = function(e){
                  if(insertedName.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the name") ?>");
                  } else if(insertedDescription.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the description") ?>");
                  } else if(insertedPrice.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the price") ?>");
                  } else if(!isValidPrice(insertedPrice.value)){
                    e.preventDefault();
                    alert("<?= translate("The price is not in the format number plus dot plus two digits") ?>");
                  } else if(insertedPercentageToDesigner.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the percentage to the designer") ?>");
                  } else if(!isValidPercentage(insertedPercentageToDesigner.value)){
                    e.preventDefault();
                    alert("<?= translate("The inserted percentage to the designer is not valid") ?>");
                  } else if(Number(insertedPercentageToDesigner.value) < 0.0 || Number(insertedPercentageToDesigner.value) > 100.0){
                    e.preventDefault();
                    alert("<?= translate("The percentage is not between z and h") ?>");
                  } 
                }
              </script>
            <?php
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
      upperPartOfThePage(translate("Edit project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
