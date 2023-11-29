<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Products of this artisan
  //This page permits to see an artisan with it's products of an artisan specifying the id of the artisan with a get
  //If this page is viewed without a get by an artisan, the artisan automatically is redirected to the page of its products
  //An atisan has more options on the page of its products such as add more products
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(getKindOfThisAccount($_GET["id"]) != "Artisan"){
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This user is not an artisan"));
    } else {
      //Page ok
      if($_GET["id"] == $_SESSION["userId"]){
        //Products of this logged user witch is an artisan
        upperPartOfThePage(translate("My products"),"");
      } else {
        //Products of this artisan (of the specified id with the get)
        upperPartOfThePage(translate("Artisan"),"jsBack");
      }
      //Show the artisan
      $userInfos = obtainUserInfos($_GET["id"]);
      $artisanInfos = obtainArtisanInfos($_GET["id"]);
      $analyzedOpeningHours = analyzeStringOpeningHours($artisanInfos["openingHours"]);
      startRow();
      startCol();
      $fileImageToVisualize = genericUserImage;
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
      }
      addLateralImage($fileImageToVisualize,$nameAndSurname);
      endCol();
      startCol();
      addParagraph($artisanInfos["shopName"]);
      addParagraphWithoutMb3($userInfos["name"]." ".$userInfos["surname"]);
      addEmailToLinkWithoutMb3($userInfos["email"]);
      addTelLinkWithoutMb3($artisanInfos["phoneNumber"]);
      addParagraphWithoutMb3($artisanInfos["address"]);
      if($analyzedOpeningHours["nowOpen"] == true){
        addParagraphWithoutMb3(translate("Now open"));
      } else {
        addParagraphWithoutMb3(translate("Now closed"));
      }
      $textButtonShowHide = translate("Show or hide more information about this artisan");
      if($_GET["id"] == $_SESSION["userId"]){
        $textButtonShowHide = translate("Show or hide more information about you");
      }
      addButtonShowHide($textButtonShowHide,"moreInformationOnThisArtisan");
      if($kindOfTheAccountInUse != "Guest" && $_GET["id"] != $_SESSION["userId"]){
        //Send message to this artisan
        addButtonLink(translate("Send message"),"./sendMessage.php?to=".$_GET["id"]);
      }
      endCol();
      endRow();
      startDivShowHide("moreInformationOnThisArtisan");
      addParagraphWithoutMb3(translate("Latitude").": ".$artisanInfos["latitude"]." ".translate("Longitude").": ".$artisanInfos["longitude"]);
      addIframeGoogleMap($artisanInfos["latitude"],$artisanInfos["longitude"]);
      addParagraphWithoutMb3Unsafe(translate("Openining hours").":<br>".str_replace("%","<br>",$analyzedOpeningHours["description"]));
      addParagraphWithoutMb3($artisanInfos["description"]);
      addCarouselImagesOfThisUser($_GET["id"]);
      endDivShowHide("moreInformationOnThisArtisan");
      //Button to add a new products
      if($_GET["id"] == $_SESSION["userId"]){
        addButtonLink(translate("Add a new product"),"./addANewProduct.php");
      }
      //Show the number of products of this artisan
      $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($_GET["id"]);
      addParagraph(translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
      //Show the products of this artisan

    }
  } else {
    if($kindOfTheAccountInUse == "Artisan"){
      //Redirect to this page of the artisan
      header('Location: ./artisan.php?id='.$_SESSION["userId"]);
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This page is visible only to artisans"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
