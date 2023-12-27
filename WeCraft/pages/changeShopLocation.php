<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change shop location
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
      //Receive post request to change the shop location
      $insertedLatitude = $_POST['insertedLatitude'];
      $insertedLongitude = $_POST['insertedLongitude'];
      $insertedAddress = trim($_POST['insertedAddress']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedLatitude == ""){
        addParagraph(translate("You have missed to insert the latitude"));
      } else if(strlen($insertedLatitude) > 24){
        addParagraph(translate("The latitude is too long"));
      } else if(!isValidCoordinate($insertedLatitude)){
        addParagraph(translate("Latitude not valid"));
      } else if($insertedLongitude == ""){
        addParagraph(translate("You have missed to insert the longitude"));
      } else if(strlen($insertedLongitude) > 24){
        addParagraph(translate("The longitude is too long"));
      } else if(!isValidCoordinate($insertedLongitude)){
        addParagraph(translate("Longitude not valid"));
      } else if($insertedAddress == ""){
        addParagraph(translate("You have missed to insert the address"));
      } else if(strlen($insertedAddress) > 49){
        addParagraph(translate("The address is too long"));
      } else {
        //Update shop location
        updateShopLocationOfAnArtisan($_SESSION["userId"],$insertedLatitude,$insertedLongitude,$insertedAddress);
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page change shop location
      //Title Change shop location
      addTitle(translate("Change shop location"));
      //Form to insert data to change the shop location
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addShortTextField(translate("Latitude"),"insertedLatitude",24);
      addShortTextField(translate("Longitude"),"insertedLongitude",24);
      addShortTextField(translate("Address"),"insertedAddress",49);
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedLatitude = document.getElementById('insertedLatitude');
          const insertedLongitude = document.getElementById('insertedLongitude');
          const insertedAddress = document.getElementById('insertedAddress');

          function isValidCoordinate(coordinate){
            const coordinateRegex = /^[0-9]+\.[0-9]+$/;
            return coordinateRegex.test(coordinate);
          }

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedLatitude.value.trim() == ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the latitude") ?>");
            } else if(!isValidCoordinate(insertedLatitude.value)){
              e.preventDefault();
              alert("<?= translate("Latitude not valid") ?>");
            } else if(insertedLongitude.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the longitude") ?>");
            } else if(!isValidCoordinate(insertedLongitude.value)){
              e.preventDefault();
              alert("<?= translate("Longitude not valid") ?>");
            } else if(insertedAddress.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the address") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
