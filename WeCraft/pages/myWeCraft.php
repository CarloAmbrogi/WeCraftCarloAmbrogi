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
    //For artisans and deigners it could be shown a carousel with all the images
    if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
      addCarouselImagesOfThisUser($_SESSION["userId"]);
    }
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
  //For customers
  if($kindOfTheAccountInUse == "Customer"){
    //AAAAAAAAAAAAA
    //AAAAAAAAAAAAA
  }
  //For artisans
  if($kindOfTheAccountInUse == "Artisan"){
    //AAAAAAAAAAAAA
    //AAAAAAAAAAAAA
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
