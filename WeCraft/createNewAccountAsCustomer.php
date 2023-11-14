<?php
  include "components/bodyOfThePage.php";
  include "components/miniComponents.php";
  include "functions/functions.php";
  include "database/access.php";

  //Create new account as customer page
  doInitialScripts();
  upperPartOfThePage(translate("Create account"),"./index.php");
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for creating a new account as customer
    $insertedEmal = $_POST['insertedEmal'];
    $insertedPassword = $_POST['insertedPassword'];
    $insertedConfirmedPassword = $_POST['insertedConfirmedPassword'];
    $passwordHash = password_hash($insertedPassword, PASSWORD_DEFAULT);
    $insertedName = $_POST['insertedName'];
    $insertedSurname = $_POST['insertedSurname'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedEmal == ""){
      addParagraph(translate("You have missed to insert the email address"));
    } else if(!isValidEmail($insertedEmal)){
      addParagraph(translate("Email address not valid"));
    } else if($insertedPassword == ""){
      addParagraph(translate("You have missed to insert the password"));
    } else if($insertedConfirmedPassword == ""){
      addParagraph(translate("You have missed to insert the confirmed password"));
    } else if($insertedName == ""){
      addParagraph(translate("You have missed to insert the name"));
    } else if($insertedSurname == ""){
      addParagraph(translate("You have missed to insert the surname"));
    } else if($insertedPassword != $insertedConfirmedPassword){
      addParagraph(translate("Password and confirmed password doesnt match"));
    } else {
      $sql = "INSERT INTO `Customer` (`id`, `email`, `password`, `name`, `surname`, `icon`) VALUES (NULL, ?, ?, ?, ?, NULL);";
      if($statement = $connectionDB->prepare($sql)){
        //Add the new account to the database
        $statement->bind_param("ssss",$insertedEmal,$passwordHash,$insertedName,$insertedSurname);
        $statement->execute();
        addParagraph(translate("DOMAILVERIFICATION"));

      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
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
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
          <div class="mb-3">
            <label for="insertedEmal" class="form-label"><?= translate("Email address") ?></label>
            <input class="form-control" id="insertedEmal" aria-describedby="emailHelp" type="text" name="insertedEmal">
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
            <input class="form-control" id="insertedName" type="text" name="insertedName">
          </div>
          <div class="mb-3">
            <label for="insertedSurname" class="form-label"><?= translate("Surname") ?></label>
            <input class="form-control" id="insertedSurname" type="text" name="insertedSurname">
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label"><?= translate("Icon") ?></label>
            <input class="form-control" type="file" id="formFile">
          </div>
          <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
          <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
        </form>
      </div>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const insertedEmal = document.getElementById('insertedEmal');
        const insertedPassword = document.getElementById('insertedPassword');
        const insertedConfirmedPassword = document.getElementById('insertedConfirmedPassword');
        const insertedName = document.getElementById('insertedName');
        const insertedSurname = document.getElementById('insertedSurname');

        function isValidEmail(email) {
          const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          return emailRegex.test(email);
        }

        //prevent sending form with errors
        form.onsubmit = function(e){
          if(insertedEmal.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the email address") ?>");
          } else if(!isValidEmail(insertedEmal.value)){
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
          }
        }
      </script>
    <?php
  }
  lowerPartOfThePage([]);
  include "database/closeConnectionDB.php";
?>
