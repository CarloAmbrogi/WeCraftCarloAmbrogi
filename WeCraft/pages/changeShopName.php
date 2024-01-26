<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change shop name
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Error"),"");
  addParagraph(translate("This page is not available"));
  /*
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only to artisans
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change the shop name
      $insertedShopName = trim($_POST['insertedShopName']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedShopName == ""){
        addParagraph(translate("You have missed to insert the shop name"));
      } else if(strlen($insertedShopName) > 24){
        addParagraph(translate("The shop name is too long"));
      } else {
        //Update shop name
        updateShopNameOfAnArtisan($_SESSION["userId"],$insertedShopName);
        addParagraph(translate("Done"));
        //sync also on Magis
        $idOfThisArtisan = $_SESSION["userId"];
        $artisanInfosUser = obtainUserInfos($_SESSION["userId"]);
        $titleMetadata = $insertedShopName." (".$artisanInfosUser["name"]." ".$artisanInfosUser["surname"].")";
        doGetRequest(MagisBaseUrl."apiForWeCraft/changeTitleMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&title=".urlencode($titleMetadata)."&url=".urlencode(WeCraftBaseUrl."pages/artisan.php?id=".$idOfThisArtisan));
      }
    } else {
      //Content of the page change shop name
      //Title Change shop name
      addTitle(translate("Change shop name"));
      //Form to insert data to change the shop name
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addShortTextField(translate("Shop name"),"insertedShopName",24);
      endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedShopName = document.getElementById('insertedShopName');

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedShopName.value.trim() == ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the shop name") ?>");
            }
          }
        </script>
      <?php
    }
  }
  */
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
