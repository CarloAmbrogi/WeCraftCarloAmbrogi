<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Home page of WeCraft
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Welcome to WeCraft"),"");
  //if you are logged in, you are redirect to your starting page (according if you are a customer or an artisan or a designer)
  $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
  // Title Welcome to WeCraft
  addTitle(translate("Welcome to WeCraft"));
  if($kindOfTheAccountInUse == "Guest"){
    addButtonLink(translate("Go to register log in page"),"./account.php");
  } else {
    $userInfos = obtainUserInfos($_SESSION["userId"]);
    $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
    addParagraph(translate("Welcome")." ".$nameAndSurname);
  }
  //Content of the home page
  //AAAAAAAAAAAAAAAAAAA
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
