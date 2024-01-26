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
        upperPartOfThePage(translate("My products"),"cookieBack");
      } else {
        //Products of this artisan (of the specified id with the get)
        upperPartOfThePage(translate("Artisan"),"cookieBack");
      }
      addScriptAddThisPageToCronology();
      //Show the artisan
      $userInfos = obtainUserInfos($_GET["id"]);
      if($userInfos["isActive"] == 0){
        addParagraph(translate("This is a deleted account")."!");
      }
      $artisanInfos = obtainArtisanInfos($_GET["id"]);
      $analyzedOpeningHours = analyzeStringOpeningHours($artisanInfos["openingHours"]);
      startRow();
      startCol();
      $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
      $fileImageToVisualize = genericUserImage;
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
      }
      addLateralImage($fileImageToVisualize,$nameAndSurname);
      endCol();
      startCol();
      addParagraph($artisanInfos["shopName"]);
      addParagraphWithoutMb3($nameAndSurname);
      if(substr($userInfos["email"],0,strlen("ACCOUNT_DELETED")) != "ACCOUNT_DELETED"){
        addEmailToLinkWithoutMb3($userInfos["email"]);
      }
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
      addButtonOnOffShowHide($textButtonShowHide,"moreInformationOnThisArtisan");
      if($kindOfTheAccountInUse != "Guest" && $_GET["id"] != $_SESSION["userId"]){
        //Send message to this artisan
        addButtonLink(translate("Send message"),"./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($_GET["id"]));
      }
      endCol();
      endRow();
      startDivShowHide("moreInformationOnThisArtisan");
      addParagraphWithoutMb3(translate("Latitude").": ".$artisanInfos["latitude"]." ".translate("Longitude").": ".$artisanInfos["longitude"]);
      addIframeGoogleMap($artisanInfos["latitude"],$artisanInfos["longitude"]);
      addParagraphWithoutMb3Unsafe(translate("Openining hours").":<br>".str_replace("%","<br>",$analyzedOpeningHours["description"]));
      addParagraphWithoutMb3Unsafe(adjustTextWithYouTubeLinks($artisanInfos["description"]));
      addCarouselImagesOfThisUser($_GET["id"]);
      endDivShowHide("moreInformationOnThisArtisan");
      //Button to add a new products
      if($_GET["id"] == $_SESSION["userId"]){
        addButtonLink(translate("Add a new product"),"./addANewProduct.php");
      }
      //Show the number of products of this artisan
      $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($_GET["id"]);
      startRow();
      startCol();
      addParagraph(translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
      endCol();
      startCol();
      addButtonOnOffShowHide(translate("Show hide not available products from this artisan"),"notAvailableProduct");
      endCol();
      endRow();
      //Show the products previews of this artisan
      $productsPreviewOfThisArtisan = obtainProductsPreviewOfThisArtisan($_GET["id"]);
      startCardGrid();
      foreach($productsPreviewOfThisArtisan as &$singleProductPreview){
        $fileImageToVisualize = genericProductImage;
        if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
          $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
        }
        $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
        $text2 = translate("Quantity available").": ".$singleProductPreview["quantity"];
        $isAvailable = true;
        if($singleProductPreview["quantity"] == "0"){
          $text2 = translate("Not available from this artisan");
          $isAvailable = false;
          if($singleProductPreview["quantityFromPatners"] == "0"){
            $text2 = translate("Not available");
          }
        }
        if(!$isAvailable){
          startDivShowHideMultiple("notAvailableProduct");
        }
        addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
        if(!$isAvailable){
          endDivShowHideMultiple();
        }
      }
      endCardGrid();
      //Show a paragraph if at least a product of this artisan is in collaboration for the design
      $numberProductsOfThisArtisanInCollaboration = numberProductsOfThisArtisanInCollaboration($_GET["id"]);
      if($numberProductsOfThisArtisanInCollaboration > 0){
        addParagraph(translate("Some of the products of this artisans are created in collaboration with other artisans or designers"));
      }
      //This artisan sells also theese products of other artisans
      $numberExchangeProductsAvailableToThisStore = obtainNumberExchangeProductsAvailableToYourStore($_GET["id"]);
      if($numberExchangeProductsAvailableToThisStore > 0){
        addParagraph(translate("This artisan sells also theese products of other artisans")." (".$numberExchangeProductsAvailableToThisStore."):");
        $exchangeProductsPreviewAvailableToThisStore = obtainExchangeProductsAvailableToYourStore($_GET["id"]);
        startCardGrid();
        foreach($exchangeProductsPreviewAvailableToThisStore as &$singleProductPreview){
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $isAvailable = true;
          if($singleProductPreview["quantityToThePatner"] == "0"){
            $isAvailable = false;
          }
          $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
          $text2 = translate("Quantity available").": ".translate("from the owner").": ".$singleProductPreview["quantity"]." ".translate("to this patner").": ".$singleProductPreview["quantityToThePatner"];
          if(!$isAvailable){
            startDivShowHideMultiple("notAvailableProduct");
          }
          addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
          if(!$isAvailable){
            endDivShowHideMultiple();
          }
        }
        endCardGrid();
      }
      //This artisan is suggests also theese products of other artisans
      $numberProductsThisArtisanIsSponsoring = numberProductsThisArtisanIsSponsoringExceptOnesIsExchangeSelling($_GET["id"]);
      if($numberProductsThisArtisanIsSponsoring > 0){
        addParagraph(translate("This artisan suggests also these products of other artisans")." (".$numberProductsThisArtisanIsSponsoring."):");
        $productsPreviewThisArtisanIsSponsoring = obtainProductsPreviewThisArtisanIsSponsoringExceptOnesIsExchangeSelling($_GET["id"]);
        startCardGrid();
        foreach($productsPreviewThisArtisanIsSponsoring as &$singleProductPreview){
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $text1 = translate("Category").": ".translate($singleProductPreview["category"]);
          $text2 = translate("Price").": ".floatToPrice($singleProductPreview["price"]);
          addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      }
      //This artisan has collaborated for the design of theese other products
      $numberOfOtherProductsThisUserIsCollaboratingFor = numberOfOtherProductsThisUserIsCollaboratingFor($_GET["id"]);
      if($numberOfOtherProductsThisUserIsCollaboratingFor > 0){
        addParagraph(translate("This artisan has collaborated for the design of theese other products")." (".$numberOfOtherProductsThisUserIsCollaboratingFor."):");
        $productsPreviewOtherProductsThisUserIsCollaboratingFor = previewOtherProductsThisUserIsCollaboratingFor($_GET["id"]);
        startCardGrid();
        foreach($productsPreviewOtherProductsThisUserIsCollaboratingFor as &$singleProductPreview){
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $text1 = translate("Category").": ".translate($singleProductPreview["category"]);
          $text2 = translate("Price").": ".floatToPrice($singleProductPreview["price"]);
          addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      }
      //Manage visibility products quantity 0
      addScriptShowHideMultiple("notAvailableProduct");
      forceThisPageReloadWhenBrowserBackButton();
      //Show button to see the collaborations for the design of this artisan
      addButtonLink(translate("See products in collaboration for the design with this artisan"),"./cooperativeDesignThisArtisan.php?id=".urlencode($_GET["id"]));
    }
  } else {
    if($kindOfTheAccountInUse == "Artisan"){
      //Redirect to this page of the artisan
      resetCronology();
      header('Location: ./artisan.php?id='.urlencode($_SESSION["userId"]));
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This page is visible only to artisans"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
