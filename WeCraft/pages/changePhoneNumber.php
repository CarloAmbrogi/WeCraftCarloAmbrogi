<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change phone number
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only to artisans
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change the phone number
      $insertedPhoneNumber = $_POST['insertedPhoneNumber'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedPhoneNumber == ""){
        addParagraph(translate("You have missed to insert the phone number"));
      } else if(strlen($insertedPhoneNumber) > 24){
        addParagraph(translate("The phone number is too long"));
      } else if(!isValidPhoneNumber($insertedPhoneNumber)){
        addParagraph(translate("Phone number not valid"));
      } else {
        //Update shop name
        updatePhoneNumberOfAnArtisan($_SESSION["userId"],$insertedPhoneNumber);
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page change phone number
      //Title Change phone number
      addTitle(translate("Change phone number"));
      //Form to insert data to change the phone number
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addShortTextField(translate("Phone number"),"insertedPhoneNumber",24);
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedPhoneNumber = document.getElementById('insertedPhoneNumber');

          function isValidPhoneNumber(phoneNumber){
            const phoneNumberRegex = /^(\+?)[0-9\ \( \)]+$/;
            return phoneNumberRegex.test(phoneNumber);
          }

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedPhoneNumber.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the phone number") ?>");
            } else if(!isValidPhoneNumber(insertedPhoneNumber.value)){
              e.preventDefault();
              alert("<?= translate("Phone number not valid") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
