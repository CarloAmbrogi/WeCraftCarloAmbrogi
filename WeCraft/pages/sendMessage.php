<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Send message
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Send message"),"cookieBack");
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to send a new message
      $insertedText = trim($_POST['insertedText']);
      $insertedChatWith = $_POST['insertedChatWith'];
      $insertedChatKind = $_POST['insertedChatKind'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(strlen($insertedText) > 2046){
        addParagraph(translate("The text is too long"));
      } else if($insertedChatWith == "" || $insertedChatKind == ""){
        addParagraph(translate("You have missed to pass the params"));
      } else {
        //Chech params are correct
        $checkIsOk = false;
        if($insertedChatKind == "personal"){
          $kindUserChatWith = getKindOfThisAccount($insertedChatWith);
          if($insertedChatWith == $_SESSION["userId"]){
            $checkIsOk = true;//chat with yourself
          }
          if($kindUserChatWith == "Designer" || $kindUserChatWith == "Artisan"){
            $checkIsOk = true;
          }
          if($kindUserChatWith == "Customer" && canYouSendMessagesToThisCustomer($_SESSION["userId"],$insertedChatWith)){
            $checkIsOk = true;
          }
        }
        if($insertedChatKind == "product"){
          if(isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$insertedChatWith)){
            $checkIsOk = true;
          }
        }
        if($insertedChatKind == "project"){
          if(isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$insertedChatWith)){
            $checkIsOk = true;
          }
        }
        if($checkIsOk == true){
          //Checks are ok, proceed sending the message
          if(isset($_FILES['insertedImage']) && $_FILES['insertedImage']['error'] == 0){
            //You have chosen to send the file image
            $fileName = $_FILES["insertedImage"]["name"];
            $fileType = $_FILES["insertedImage"]["type"];
            $fileSize = $_FILES['insertedImage']['size'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $insertCorrectlyTheImage = false;
            if($fileSize > maxSizeForAFile){
              addParagraph(translate("The file is too big"));
            } else if(!array_key_exists($fileExtension, permittedExtensions)){
              addParagraph(translate("The extension of the file is not an image"));
            } else if(!in_array($fileType, permittedExtensions)){
              addParagraph(translate("The file is not an image"));
            } else {
              $insertCorrectlyTheImage = true;
              //Send this message with also the image
              $imgData = file_get_contents($_FILES['insertedImage']['tmp_name']);
              sendMessageWithImage($_SESSION["userId"],$insertedChatKind,$insertedChatWith,$insertedText,$fileExtension,$imgData);
              //redirect to the chat
              addRedirect("./chat.php?chatKind=".urlencode($insertedChatKind)."&chatWith=".urlencode($insertedChatWith));
            }
            if($insertCorrectlyTheImage == false){
              addParagraph(translate("There is a problem in the image and so its not possible to send this message"));
              addButtonLink(translate("Send new message"),"./sendMessage.php?chatKind=".urlencode($insertedChatKind)."&chatWith=".urlencode($insertedChatWith));
            }
          } else {
            //You have chosen to not send the file image
            if($insertedText != ""){
              //Send this message without also the image
              sendMessageWithoutImage($_SESSION["userId"],$insertedChatKind,$insertedChatWith,$insertedText);
              //redirect to the chat
              addRedirect("./chat.php?chatKind=".urlencode($insertedChatKind)."&chatWith=".urlencode($insertedChatWith));
            } else {
              addParagraph(translate("This message is empty"));
            }
          }
        } else {
          addParagraph(translate("Some params are wrong"));
        }
      }
    } else {
      //Page without post request
      //Check get params available
      if(isset($_GET["chatWith"]) && isset($_GET["chatKind"])){
        //Chech get params are correct
        $checkIsOk = false;
        if($_GET["chatKind"] == "personal"){
          $kindUserChatWith = getKindOfThisAccount($_GET["chatWith"]);
          if($_GET["chatWith"] == $_SESSION["userId"]){
            $checkIsOk = true;//chat with yourself
          }
          if($kindUserChatWith == "Designer" || $kindUserChatWith == "Artisan"){
            $checkIsOk = true;
          }
          if($kindUserChatWith == "Customer" && canYouSendMessagesToThisCustomer($_SESSION["userId"],$_GET["chatWith"])){
            $checkIsOk = true;
          }
        }
        if($_GET["chatKind"] == "product"){
          if(isThisUserCollaboratingForTheProductionOfThisProduct($_SESSION["userId"],$_GET["chatWith"])){
            $checkIsOk = true;
          }
        }
        if($_GET["chatKind"] == "project"){
          if(isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$_GET["chatWith"])){
            $checkIsOk = true;
          }
        }
        if($checkIsOk == true){
          //Content of the send message
          //Show with who you are chatting
          $paragraphYouAreChattingWith = translate("Send a message to");
          if($_GET["chatKind"] == "personal"){
            $userInfos = obtainUserInfos($_GET["chatWith"]);
            $paragraphYouAreChattingWith.=" ".$userInfos["name"]." ".$userInfos["surname"].":";
          }
          if($_GET["chatKind"] == "product"){
            $productInfos = obtainProductInfos($_GET["chatWith"]);
            $paragraphYouAreChattingWith.=" ".$productInfos["name"]." (".translate("group")."):";
          }
          if($_GET["chatKind"] == "project"){
            $projectInfos = obtainProjectInfos($_GET["chatWith"]);
            $paragraphYouAreChattingWith.=" ".$projectInfos["name"]." (".translate("group")."):";
          }
          addParagraph($paragraphYouAreChattingWith);
          //Form to insert data to send a new message
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addLongTextField(translate("Text"),"insertedText",2046);
          addFileField(translate("Image optional"),"insertedImage");
          addHiddenField("insertedChatWith",$_GET["chatWith"]);
          addHiddenField("insertedChatKind",$_GET["chatKind"]);
          endForm(translate("Submit"));
          ?>
            <script>
              //form inserted parameters
              const form = document.querySelector('form');
              const insertedText = document.getElementById('insertedText');
              const inputFile = document.getElementById('formFile');
              
              function getFileExtension(filename){
                return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
              }

              //prevent sending form with errors
              form.onsubmit = function(e){
                if(insertedText.value === "" && !inputFile.files[0]){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the text of the message or the image") ?>");
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
        } else {
          //Show error you cant chat here
          if($_GET["chatKind"] == "personal"){
            if($kindOfTheAccountInUse == "Customer"){
              addParagraph(translate("A customer cant chat with other customers"));
            } else {
              addParagraph(translate("You cant chat with customer who hasnt started a chat with you"));
            }
          } else if($_GET["chatKind"] == "product" || $_GET["chatKind"] == "project"){
            addParagraph(translate("You are not in this group for this cooperative production"));
          } else {
            addParagraph(translate("This kind of chat doesnt exists"));
          }
        }
      } else {
        addParagraph(translate("You have missed to pass the get params"));
      }
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
