<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Delete icon
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
      //Receive post request to delete your icon
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Delete your icon
        deleteIconOfAnUser($_SESSION["userId"]);
        addParagraph(translate("Done"));
        if($kindOfTheAccountInUse == "Artisan"){
          //sync also on Magis
          $imageUrl = genericUserImage;
          $idOfThisArtisan = $_SESSION["userId"];
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeImageUrlMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&imageUrl=".urlencode($imageUrl)."&url=".urlencode(WeCraftBaseUrl."pages/artisan.php?id=".$idOfThisArtisan));  
        }
      }
    } else {
      //Content of the page delete icon
      $userInfos = obtainUserInfos($_SESSION["userId"]);
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        //Title Delete icon
        addTitle(translate("Delete icon"));
        //Form to insert data to delete the icon
        startForm1();
        startForm2($_SERVER['PHP_SELF']);
        endForm(translate("Delete icon"));
      } else {
        addParagraph(translate("You have not set the icon"));
      }
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
