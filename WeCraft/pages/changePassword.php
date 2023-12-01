<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change password
  doInitialScripts();
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change the password
      $insertedOldPassword = $_POST['insertedOldPassword'];
      $insertedPassword = $_POST['insertedPassword'];
      $insertedConfirmedPassword = $_POST['insertedConfirmedPassword'];
      $passwordHash = password_hash($insertedPassword, PASSWORD_DEFAULT);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedOldPassword == ""){
        addParagraph(translate("You have missed to insert the old password"));
      } else if($insertedPassword == ""){
        addParagraph(translate("You have missed to insert the password"));
      } else if($insertedConfirmedPassword == ""){
        addParagraph(translate("You have missed to insert the confirmed password"));
      } else if($insertedPassword != $insertedConfirmedPassword){
        addParagraph(translate("Password and confirmed password doesnt match"));
      } else {
        //Check if the password is correct
        $isLoginValid = isPasswordValidUserId($_SESSION["userId"], $insertedOldPassword);
        if($isLoginValid){
          //Update password
          updatePasswordOfAnUser($_SESSION["userId"],$passwordHash);
          addParagraph(translate("Done"));
        } else {
          addParagraph(translate("Wrong old password"));
        }
      }
    } else {
      //Content of the page change password
      //Title Change password
      addTitle(translate("Change password"));
      //Form to insert data to change name and surname
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addPasswordField(translate("Old password"),"insertedOldPassword");
      addPasswordField(translate("New password"),"insertedOldPassword");
      addPasswordField(translate("Confirm new password"),"insertedConfirmedPassword");
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedOldPassword = document.getElementById('insertedOldPassword');
          const insertedPassword = document.getElementById('insertedPassword');
          const insertedConfirmedPassword = document.getElementById('insertedConfirmedPassword');

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedOldPassword.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the old password") ?>");
            } else if(insertedPassword.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the new password") ?>");
            } else if(insertedConfirmedPassword.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the confirmed new password") ?>");
            } else if(insertedPassword.value !== insertedConfirmedPassword.value){
              e.preventDefault();
              alert("<?= translate("Password and confirmed password doesnt match") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
