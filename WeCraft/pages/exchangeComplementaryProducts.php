<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Exchange complementary products
  doInitialScripts();
  addScriptAddThisPageToCronology();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperate"),"./cooperate.php");
    addTitle(translate("Exchange complementary products"));
    addParagraph(translate("Here will be shown the products of other artisans you are selling in your store"));
    //Show exchange products available to your store
    $exchangeProductsPreviewAvailableToYourStore = obtainExchangeProductsAvailableToYourStore($_SESSION["userId"]);
    startCardGrid();
    foreach($exchangeProductsPreviewAvailableToYourStore as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available").": ".translate("from the owner").": ".$singleProductPreview["quantity"]." ".translate("to the patner").": ".$singleProductPreview["quantityToThePatner"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    addParagraph(translate("If you receive from an artisan an his product you can start sell it in your store and you can notify this on WeCraft from the page of that product"));
    addParagraph(translate("Here there are some suggestions of possible artisans who sell complemetary products to yours").":");
    //Show these suggested artisans
    $previewArtisansWhoCouldBeComplementaryToThisArtisan = obtainPreviewArtisansWhoCouldBeComplementaryToThisArtisan($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($previewArtisansWhoCouldBeComplementaryToThisArtisan as &$singleArtisanPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
    }
    endCardGrid();
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
    //Show products of the artisan whitch are sold also by other artisans
    addTitle(translate("Your products whitch are sold also by other artisans"));
    addParagraph(translate("You can click on a product and then if you want you can stop the sell of that product from certains artisans"));
    $exchangeProductsPreviewAvailableToYourStore = obtainYourProductsWitchAreSoldAlsoByOtherArtisans($_SESSION["userId"]);
    startCardGrid();
    foreach($exchangeProductsPreviewAvailableToYourStore as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available").": ".translate("from the owner").": ".$singleProductPreview["quantity"]." ".translate("to the patner").": ".$singleProductPreview["quantityToThePatner"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
