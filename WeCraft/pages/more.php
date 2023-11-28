<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //More
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("More"),"");
  //For all users except guests
  if($kindOfTheAccountInUse != "Guest"){
    $userInfos = obtainUserInfos($_SESSION["userId"]);
    $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
    addParagraph(translate("Account").": ".$nameAndSurname." ".$userInfos["email"]);
    addParagraph(translate("Kind").": ".translate("S".$kindOfTheAccountInUse));
    $fileImageToVisualize = genericUserImage;
    if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
      $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
    }
    addImage($fileImageToVisualize,$nameAndSurname);
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

  }
  //For artisans
  if($kindOfTheAccountInUse == "Artisan"){

  }
  //For everyone
  addButtonLink(translate("Log out"),"./logout.php");
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
