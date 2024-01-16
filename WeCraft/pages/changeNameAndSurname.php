<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change name and surname
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse == "Guest"){
    //This page is not visible if you are a guest
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change name and surname
      $insertedName = trim($_POST['insertedName']);
      $insertedSurname = trim($_POST['insertedSurname']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedName == ""){
        addParagraph(translate("You have missed to insert the name"));
      } else if(strlen($insertedName) > 24){
        addParagraph(translate("The name is too long"));
      } else if($insertedSurname == ""){
        addParagraph(translate("You have missed to insert the surname"));
      } else if(strlen($insertedSurname) > 24){
        addParagraph(translate("The surname is too long"));
      } else {
        //Update name and surname
        updateNameAndSurnameOfAnUser($_SESSION["userId"],$insertedName,$insertedSurname);
        addParagraph(translate("Done"));
        if($kindOfTheAccountInUse == "Artisan"){
          //sync also on Magis
          $artisanInfos = obtainArtisanInfos($_SESSION["userId"]);
          $titleMetadata = $artisanInfos["shopName"]." (".$insertedName." ".$insertedSurname.")";
          $idOfThisArtisan = $_SESSION["userId"];
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeTitleMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&title=".urlencode($titleMetadata)."&url=".urlencode(WeCraftBaseUrl."pages/artisan.php?id=".$idOfThisArtisan));
        }
      }
    } else {
      //Content of the page change name and surname
      //Title Change name and surname
      addTitle(translate("Change name and surname"));
      //Form to insert data to change name and surname
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addShortTextField(translate("Name"),"insertedName",24);
      addShortTextField(translate("Surname"),"insertedSurname",24);
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedName = document.getElementById('insertedName');
          const insertedSurname = document.getElementById('insertedSurname');

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedName.value.trim() == ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the name") ?>");
            } else if(insertedSurname.valu.trim() == ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the surname") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
