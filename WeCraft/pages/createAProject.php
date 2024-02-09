<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //This page is to create a project by the id of the customer
  //This page is available only for designers
  //This page is reachable from the chat with a customer
  //A designer can create a project only for customer with witch has chatted before
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for creating a project
    $insertedCustomerId = $_POST['insertedCustomerId'];
    upperPartOfThePage(translate("Create a project"),"./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($insertedCustomerId));
    $insertedName = trim($_POST['insertedName']);
    $insertedDescription = trim($_POST['insertedDescription']);
    $insertedIconExtension = $_POST['insertedIconExtension'];
    $insertedIcon = $_POST['insertedIcon'];
    $insertedPrice = $_POST['insertedPrice'];
    $insertedPercentageToDesigner = $_POST['insertedPercentageToDesigner'];
    $insertedEstimatedTime = $_POST['insertedEstimatedTime'];
    $insertedEstimatedTimeDuration = $_POST['insertedEstimatedTimeDuration'];
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
    } else if($insertedPrice == ""){
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
    } else if($insertedEstimatedTime == ""){
      addParagraph(translate("You have missed to insert the estimated time"));
    } else if(!isValidQuantity($insertedEstimatedTime)){
      addParagraph(translate("The estimated time is not a valid number"));
    } else if($insertedEstimatedTime == 0){
      addParagraph(translate("The estimated time cant be zero"));
    } else if($insertedEstimatedTimeDuration != "days" && $insertedEstimatedTimeDuration != "weeks"){
      addParagraph(translate("The estimated time duration is wrong"));
    } else {
      //Calc estimated time
      $calcEstimatedTime = $insertedEstimatedTime;
      if($insertedEstimatedTimeDuration == "days"){
        $calcEstimatedTime = $calcEstimatedTime * 24 * 60 * 60;
      }
      if($insertedEstimatedTimeDuration == "weeks"){
        $calcEstimatedTime = $calcEstimatedTime * 24 * 60 * 60 * 7;
      }
      //Verify that the user is a designer
      if($kindOfTheAccountInUse == "Designer"){
        //Verify that this user exists and it is a customer
        if(getKindOfThisAccount($insertedCustomerId) == "Customer"){
          //Verify you have a chat with this customer
          if(canYouSendMessagesToThisCustomer($_SESSION["userId"],$insertedCustomerId)){
            //Add the new project
            if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
              //You have chosen to send the file icon
              $fileName = $_FILES["insertedIcon"]["name"];
              $fileType = $_FILES["insertedIcon"]["type"];
              $fileSize = $_FILES['insertedIcon']['size'];
              $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
              $insertCorrectlyTheIcon = false;
              if($fileSize > maxSizeForAFile){
                addParagraph(translate("The file is too big"));
              } else if(!array_key_exists($fileExtension, permittedExtensions)){
                addParagraph(translate("The extension of the file is not an image"));
              } else if(!in_array($fileType, permittedExtensions)){
                addParagraph(translate("The file is not an image"));
              } else {
                $insertCorrectlyTheIcon = true;
                //add the new project with file icon
                $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
                addANewProjectWithIcon($_SESSION["userId"],$insertedCustomerId,$insertedName,$insertedDescription,$fileExtension,$imgData,$insertedPrice,$insertedPercentageToDesigner,$calcEstimatedTime);
                addParagraph(translate("Your data has been loaded correctly"));
              }
              if($insertCorrectlyTheIcon == false){
                //add the new product without file icon (because of error in the icon)
                addANewProjectWithoutIcon($_SESSION["userId"],$insertedCustomerId,$insertedName,$insertedDescription,$insertedPrice,$insertedPercentageToDesigner,$calcEstimatedTime);
                addParagraph(translate("Your data has been loaded correctly except for the icon but you will be able to change the icon later"));
              }
            } else {
              //add the new product without file icon
              addParagraph(translate("Your data has been loaded correctly"));
              addANewProjectWithoutIcon($_SESSION["userId"],$insertedCustomerId,$insertedName,$insertedDescription,$insertedPrice,$insertedPercentageToDesigner,$calcEstimatedTime);
            }
            //Show button to go to the personalized items page v1
            addParagraph(translate("You can see the project you have created in the personalized item page and youll be able to present this project to some artisans"));
            addButtonLink(translate("Go to personalized items page"),"./personalizedItems.php?insertedCategory=v1");
          } else {
            addParagraph(translate("You cant create a project for a customer who hasnt started a chat with you"));
          }
        } else {
          addParagraph(translate("This user is not a customer"));
        }
      } else {
        addParagraph(translate("This page is visible only to designers"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Create a project"),"./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($_GET["id"]));
      //Verify that the user is a designer
      if($kindOfTheAccountInUse == "Designer"){
        //Verify that this user exists and it is a customer
        if(getKindOfThisAccount($_GET["id"]) == "Customer"){
          //Verify you have a chat with this customer
          if(canYouSendMessagesToThisCustomer($_SESSION["userId"],$_GET["id"])){
            //Content of this page
            $userInfos = obtainUserInfos($_GET["id"]);
            addParagraph(translate("Create a project for")." ".$userInfos["name"]." ".$userInfos["surname"]);
            //Form to insert data to create a project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addShortTextField(translate("Name"),"insertedName",24);
            addLongTextField(translate("Description"),"insertedDescription",2046);
            addShortTextField(translate("Price"),"insertedPrice",24);
            addShortTextField(translate("Percentage to the designer"),"insertedPercentageToDesigner",5);
            startSquare();
            ?>
              <div class="mb-3">
                <label for="insertedEstimatedTime" class="form-label"><?= translate("Estimated time") ?></label>
                <input class="form-control" id="insertedEstimatedTime" type="text" name="insertedEstimatedTime" maxlength="24">
                <select id="insertedEstimatedTimeDuration" name="insertedEstimatedTimeDuration">
                  <option value="days"><?= translate("days") ?></option>
                  <option value="weeks"><?= translate("weeks") ?></option>
                </select>
              </div>
            <?php
            endSquare();
            addHiddenField("insertedCustomerId",$_GET["id"]);
            addFileField(translate("Icon optional"),"insertedIcon");
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const insertedName = document.getElementById('insertedName');
                const insertedDescription = document.getElementById('insertedDescription');
                const insertedPrice = document.getElementById('insertedPrice');
                const insertedPercentageToDesigner = document.getElementById('insertedPercentageToDesigner');
                const insertedEstimatedTime = document.getElementById('insertedEstimatedTime');
      
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
                  } else if(insertedEstimatedTime.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the estimated time") ?>");
                  } else if(!isValidQuantity(insertedEstimatedTime.value)){
                    e.preventDefault();
                    alert("<?= translate("The estimated time is not a valid number") ?>");
                  } else if(insertedEstimatedTime.value == 0){
                    e.preventDefault();
                    alert("<?= translate("The estimated time cant be zero") ?>");
                  } else if(inputFile.files[0]){
                    let file = inputFile.files[0];
                    let fileSize = file.size;
                    let fileName = file.name;
                    let fileExtension = getFileExtension(fileName);
                    const permittedExtensions = ["jpg","jpeg","gif","png","webp","heic"];
                    if(fileSize > <?= maxSizeForAFile ?>){
                      e.preventDefault();
                      alert("<?= translate("The file is too big") ?>");
                    } else if(!permittedExtensions.includes(fileExtension)){
                      e.preventDefault();
                      alert("<?= translate("The extension of the file is not an image") ?>");
                    }
                  }
                }
              </script>
            <?php
            //End main content of this page
          } else {
            addParagraph(translate("You cant create a project for a customer who hasnt started a chat with you"));
          }
        } else {
          addParagraph(translate("This user is not a customer"));
        }
      } else {
        addParagraph(translate("This page is visible only to designers"));
      }
    } else {
      //You have missed to specify the get param id of the customer
      upperPartOfThePage(translate("Create a project"),"");
      addParagraph(translate("You have missed to specify the get param id of the customer"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
