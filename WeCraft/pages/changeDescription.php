<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change description
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Designer" && $kindOfTheAccountInUse != "Artisan"){
    //This page is visible only to artisans and designers
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change name and surname
      $insertedDescription = trim($_POST['insertedDescription']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedDescription == ""){
        addParagraph(translate("You have missed to insert the description"));
      } else if(strlen($insertedDescription) > 2046){
        addParagraph(translate("The description is too long"));
      } else {
        //Update description
        if($kindOfTheAccountInUse == "Designer"){
          updateDescriptionOfADesigner($_SESSION["userId"],$insertedDescription);
        }
        if($kindOfTheAccountInUse == "Artisan"){
          updateDescriptionOfAnArtisan($_SESSION["userId"],$insertedDescription);
          //sync also on Magis
          $idOfThisArtisan = $_SESSION["userId"];
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeDescriptionMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&description=".urlencode($insertedDescription)."&url=".urlencode(WeCraftBaseUrl."pages/artisan.php?id=".$idOfThisArtisan));
        }
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page change description
      //Title Change description
      addTitle(translate("Change description"));
      //Form to insert data to change the description
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addLongTextField(translate("Description"),"insertedDescription",2046);
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedDescription = document.getElementById('insertedDescription');

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedDescription.value.trim() == ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the description") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
