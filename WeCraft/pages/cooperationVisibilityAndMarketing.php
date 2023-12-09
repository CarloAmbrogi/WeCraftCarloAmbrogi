<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperation for visibility and marketing
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperate"),"./cooperate.php");
    addTitle(translate("Cooperation for visibility and marketing"));
    addParagraph(translate("Here will be shown the products of other artisans you are sponsoring on your artisan page"));
    //Show the products you are sponsoring
    $productsPreviewThisArtisanIsSponsoring = obtainProductsPreviewThisArtisanIsSponsoring($_SESSION["userId"]);
    startCardGrid();
    foreach($productsPreviewThisArtisanIsSponsoring as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available").": ".$singleProductPreview["quantity"];
      if($singleProductPreview["quantity"] == "0"){
        $text2 = translate("Not available");
      }
      addACardForTheGrid("./product.php?id=".$singleProductPreview["id"],$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    addParagraph(translate("If you find an interesting product you want to sponsor on your artisan page, you can add it from the product page"));
    addTitle(translate("Suggested products"));
    addParagraph(translate("Here there are some suggested products you could sponsor on your artisan page")."...");
    addParagraph(translate("Products of artisans who are sponsoring some of your products").":");
    //Show some products of artisans who are sponsoring your products
    $productsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts = obtainProductsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts($_SESSION["userId"]);
    startCardGrid();
    foreach($productsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available").": ".$singleProductPreview["quantity"];
      if($singleProductPreview["quantity"] == "0"){
        $text2 = translate("Not available");
      }
      addACardForTheGrid("./product.php?id=".$singleProductPreview["id"],$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    addParagraph(translate("Products of artisans whoose you are sponsoring some other products").":");
    //Show some products of artisans whoose you are sponsoring some other products
    $productsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts = obtainProductsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts($_SESSION["userId"]);
    startCardGrid();
    foreach($productsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available").": ".$singleProductPreview["quantity"];
      if($singleProductPreview["quantity"] == "0"){
        $text2 = translate("Not available");
      }
      addACardForTheGrid("./product.php?id=".$singleProductPreview["id"],$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
