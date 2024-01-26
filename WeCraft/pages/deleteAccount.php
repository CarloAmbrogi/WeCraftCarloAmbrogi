<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Delete account
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Delete account"),"./myWeCraft.php");
  if($kindOfTheAccountInUse == "Guest"){
    addParagraph(translate("For this functionality you have to do the log in"));
  } else {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to delete your account
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Delete your account
        deleteAccount($_SESSION["userId"]);
        addParagraph(translate("Done"));
        addButtonLink(translate("Return to home"),"./index.php");
        if($kindOfTheAccountInUse == "Artisan"){
          //Make all products anaviable
          makeAllProductsAnaviable($_SESSION["userId"]);
          stopSponsoringItems($_SESSION["userId"]);
          stopResellingItems($_SESSION["userId"]);
        }
        if($kindOfTheAccountInUse == "Artisan"){
          //sync also on Magis
          $artisanInfos = obtainArtisanInfos($_SESSION["userId"]);
          $uuu = MagisBaseUrl."apiForWeCraft/deletePoi.php?password=".urlencode(PasswordCommunicationWithMagis)."&oldAddress=".urlencode($artisanInfos["address"])."&oldLatitude=".urlencode($artisanInfos["latitude"])."&oldLongitude=".urlencode($artisanInfos["longitude"]);
          var_dump($uuu);
          doGetRequest($uuu);
        }
        //destroy session
        unset($_SESSION["userId"]);
        session_destroy();
      }
    } else {
      //Content of the page delete account
      $userInfos = obtainUserInfos($_SESSION["userId"]);
      //Title Delete account
      addTitle(translate("Delete account"));
      //Form to insert data to delete the account
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      endForm(translate("Delete account"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
