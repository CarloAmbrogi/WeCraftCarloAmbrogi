<?php
  include "components/bodyOfThePage.php";
  include "components/miniComponents.php";
  include "functions/costants.php";
  include "functions/functions.php";
  include "database/access.php";
  include "database/functions.php";

  //Create new account as customer page
  doInitialScripts();
  upperPartOfThePage(translate("Create account"),"./index.php");
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for creating a new account as customer
    $insertedEmail = $_POST['insertedEmail'];
    $insertedPassword = $_POST['insertedPassword'];
    $insertedConfirmedPassword = $_POST['insertedConfirmedPassword'];
    $passwordHash = password_hash($insertedPassword, PASSWORD_DEFAULT);
    $insertedName = $_POST['insertedName'];
    $insertedSurname = $_POST['insertedSurname'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedEmail == ""){
      addParagraph(translate("You have missed to insert the email address"));
    } else if(strlen($insertedEmail) > 49){
      addParagraph(translate("The email address is too long"));
    } else if(!isValidEmail($insertedEmail)){
      addParagraph(translate("Email address not valid"));
    } else if($insertedPassword == ""){
      addParagraph(translate("You have missed to insert the password"));
    } else if($insertedConfirmedPassword == ""){
      addParagraph(translate("You have missed to insert the confirmed password"));
    } else if($insertedName == ""){
      addParagraph(translate("You have missed to insert the name"));
    } else if(strlen($insertedName) > 24){
      addParagraph(translate("The name is too long"));
    } else if($insertedSurname == ""){
      addParagraph(translate("You have missed to insert the surname"));
    } else if(strlen($insertedSurname) > 24){
      addParagraph(translate("The surname is too long"));
    } else if($insertedPassword != $insertedConfirmedPassword){
      addParagraph(translate("Password and confirmed password doesnt match"));
    } else if(hasThisEmailAddressBeenUsed($insertedEmail)){
      addParagraph(translate("This email address has been used"));
    } else {      
      //Check on the file icon
      if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
        //You have chosen to send the file icon
        $fileName = $_FILES["insertedIcon"]["name"];
        $fileType = $_FILES["insertedIcon"]["type"];
        $fileSize = $_FILES['insertedIcon']['size'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if($fileSize < maxSizeForAFile){
          if(array_key_exists($fileExtension, permittedExtensions)){
            if(in_array($fileType, permittedExtensions)){
              //add new account with file icon
              $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
              addANewCustomerWithIcon($insertedEmail,$passwordHash,$insertedName,$fileExtension,$imgData);
              addParagraph(translate("Your account has been created correctly AAAAAAAAAAAAAAAAAA TODO mail verification"));
            } else {
              addParagraph(translate("The file is not an image"));
              addANewCustomerWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname);
              addParagraph(translate("Your account has been created anyway AAAAAAAAAAAAAAAAAA TODO mail verification"));
            }
          } else {
            addParagraph(translate("The extension of the file is not an image"));
            addANewCustomerWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname);
            addParagraph(translate("Your account has been created anyway AAAAAAAAAAAAAAAAAA TODO mail verification"));
          }
        } else {
          addParagraph(translate("The file is too big"));
          addANewCustomerWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname);
          addParagraph(translate("Your account has been created anyway AAAAAAAAAAAAAAAAAA TODO mail verification"));
        }
      } else {
        //create account without file icon
        addParagraph(translate("Your account has been created correctly AAAAAAAAAAAAAAAAAA TODO mail verification"));
        addANewCustomerWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname);
      }
    }
  } else {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
    ?>
      <!-- Title Create new account as customer -->
      <?php addTitle($GLOBALS['$L']["Create new account as customer"]); ?>
      <!-- Form to insert data to create a new account as customer -->
      <div class="row mb-3">
        <p><?= $GLOBALS['$L']["Insert your data:"] ?></p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="insertedEmail" class="form-label"><?= translate("Email address") ?></label>
            <input class="form-control" id="insertedEmail" aria-describedby="emailHelp" type="text" name="insertedEmail" maxlength="49">
          </div>
          <div class="mb-3">
            <label for="insertedPassword" class="form-label"><?= translate("Password") ?></label>
            <input type="password" class="form-control" id="insertedPassword" type="text" name="insertedPassword">
          </div>
          <div class="mb-3">
            <label for="insertedConfirmedPassword" class="form-label"><?= translate("Confirm password") ?></label>
            <input type="password" class="form-control" id="insertedConfirmedPassword" type="text" name="insertedConfirmedPassword">
          </div>
          <div class="mb-3">
            <label for="insertedName" class="form-label"><?= translate("Name") ?></label>
            <input class="form-control" id="insertedName" type="text" name="insertedName" maxlength="24">
          </div>
          <div class="mb-3">
            <label for="insertedSurname" class="form-label"><?= translate("Surname") ?></label>
            <input class="form-control" id="insertedSurname" type="text" name="insertedSurname" maxlength="24">
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label"><?= translate("Icon optional") ?></label>
            <input class="form-control" type="file" id="formFile" name="insertedIcon">
          </div>
          <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
          <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
        </form>
      </div>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const insertedEmail = document.getElementById('insertedEmail');
        const insertedPassword = document.getElementById('insertedPassword');
        const insertedConfirmedPassword = document.getElementById('insertedConfirmedPassword');
        const insertedName = document.getElementById('insertedName');
        const insertedSurname = document.getElementById('insertedSurname');
        const inputFile = document.getElementById('formFile');

        function isValidEmail(email){
          const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          return emailRegex.test(email);
        }

        function getFileExtension(filename){
          return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
        }

        let emailAddressBeenUsedCheck = false;

        //prevent sending form with errors
        form.onsubmit = function(e){
          if(insertedEmail.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the email address") ?>");
          } else if(!isValidEmail(insertedEmail.value)){
            e.preventDefault();
            alert("<?= translate("Email address not valid") ?>");
          } else if(insertedPassword.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the password") ?>");
          } else if(insertedConfirmedPassword.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the confirmed password") ?>");
          } else if(insertedName.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the name") ?>");
          } else if(insertedSurname.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the surname") ?>");
          } else if(insertedPassword.value !== insertedConfirmedPassword.value){
            e.preventDefault();
            alert("<?= translate("Password and confirmed password doesnt match") ?>");
          } else if(!emailAddressBeenUsedCheck) {
            //prevent sending form if the email address has been used
            e.preventDefault();
            let requestUrl = "./api/hasThisEmailAddressBeenUsed.php?thisEmailAddress=" + insertedEmail.value;
            let request = new XMLHttpRequest();
            request.open("GET", requestUrl);
            request.responseType = "json";
            request.send();
            request.onload = function(){
              const result = request.response;
              if(result[0].hasThisEmailAddressBeenUsed == 0){
                emailAddressBeenUsedCheck = true;
                $("#submit").unbind('click').click();
              } else {
                alert("<?= translate("This email address has been used") ?>");
              }
            }
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
  }
  lowerPartOfThePage([]);
  include "database/closeConnectionDB.php";
?>
