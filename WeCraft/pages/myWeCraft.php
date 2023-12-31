<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //My WeCraft
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("My WeCraft"),"");
  //For guests
  if($kindOfTheAccountInUse == "Guest"){
    addParagraph(translate("For more functions register or do the log in"));
    addButtonLink(translate("Go to register log in page"),"./account.php");
    addButtonLink(translate("Return to home"),"./index.php");
  }
  //For all users except guests
  if($kindOfTheAccountInUse != "Guest"){
    startRow();
    startCol();
    $userInfos = obtainUserInfos($_SESSION["userId"]);
    $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
    addParagraph(translate("Account").": ".$nameAndSurname." ".$userInfos["email"]);
    addParagraph(translate("Kind").": ".translate("S".$kindOfTheAccountInUse));
    $fileImageToVisualize = genericUserImage;
    if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
      $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
    }
    addImage($fileImageToVisualize,$nameAndSurname);
    endCol();
    startCol();
    addButtonLink(translate("Return to home without doing the log out"),"./index.php");//Go to home page
    addButtonLink(translate("Log out"),"./logout.php");//Log out
    endCol();
    endRow();
    //Go to your artisan / designer page
    if($kindOfTheAccountInUse == "Artisan"){
      addButtonLink(translate("Go to your artisan page"),"./artisan.php");
    }
    if($kindOfTheAccountInUse == "Designer"){
      addButtonLink(translate("Go to your designer page"),"./designer.php");
    }
    //User settings
    addButtonLink(translate("Change name and surname"),"./changeNameAndSurname.php");
    if($fileImageToVisualize != genericUserImage){
      addButtonLink(translate("Delete icon"),"./deleteIcon.php");
    }
    addButtonLink(translate("Change icon"),"./changeIcon.php");
    addButtonLink(translate("Change password"),"./changePassword.php");
  }
  //For artisans and deigners
  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    addButtonLink(translate("Add images"),"./addImages.php");
    $numberOfImages = getNumberImagesOfThisUser($_SESSION["userId"]);
    if($numberOfImages > 0){
      addButtonLink(translate("Remove images"),"./removeImages.php");
    }
  }
  //For customers and other users
  if($kindOfTheAccountInUse != "Guest"){
    addButtonLink(translate("Purchases cronology"),"./purchasesCronology.php");
  }
  //For artisans
  if($kindOfTheAccountInUse == "Artisan"){
    addButtonLink(translate("Change description"),"./changeDescription.php");
    addButtonLink(translate("Change shop name"),"./changeShopName.php");
    addButtonLink(translate("Change opening hours"),"./changeOpeningHours.php");
    addButtonLink(translate("Change phone number"),"./changePhoneNumber.php");
    addButtonLink(translate("Change shop location"),"./changeShopLocation.php");
    //AAAAAAAAAAAAA
  }
  //For Designers
  if($kindOfTheAccountInUse == "Designer"){
    addButtonLink(translate("Change description"),"./changeDescription.php");
    //AAAAAAAAAAAAA
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
