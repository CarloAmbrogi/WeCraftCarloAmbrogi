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
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]);
      $text2 = translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    addParagraph(translate("If you find an interesting product you want to sponsor on your artisan page, you can add it from the product page"));
    //Index
    addButtonLink(translate("Suggested products"),"#suggestedProducts");
    addButtonLink(translate("Suggested artisans"),"#suggestedArtisans");
    addButtonLink(translate("Cooperating artisans"),"#cooperatingArtisans");
    //Suggested products
    addSubtopicIndex("suggestedProducts");
    addTitle(translate("Suggested products"));
    addParagraph(translate("Here there are some suggested products you could sponsor on your artisan page")."...");
    addParagraph(translate("Products of artisans who are sponsoring some of your products").":");
    //Show some products of artisans who are sponsoring your products
    $productsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts = obtainProductsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($productsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts as &$singleProductPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
    addParagraph(translate("Products of artisans whoose you are sponsoring some other products").":");
    //Show some products of artisans whoose you are sponsoring some other products
    $productsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts = obtainProductsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($productsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts as &$singleProductPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
    //Suggested artisans
    addSubtopicIndex("suggestedArtisans");
    addTitle(translate("Suggested artisans"));
    addParagraph(translate("Here there are some suggested artisans you could sponsor some of their products on your artisan page")."...");
    addParagraph(translate("Artisans who are sponsoring some of your products").":");
    //Show artisans who are sponsoring some of your products
    $previewArtisansWhoAreSponsoringSomeOfTheProductsOfThisArtisan = obtainPreviewArtisansWhoAreSponsoringSomeOfTheProductsOfThisArtisan($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($previewArtisansWhoAreSponsoringSomeOfTheProductsOfThisArtisan as &$singleArtisanPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Number of your products this artisan is sponsoring").": ".$singleArtisanPreview["numberProductsIsSponsoring"]);
    }
    endCardGrid();
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
    addParagraph(translate("Artisans whoose you are sponsoring some products").":");
    //Show artisans whoose you are sponsoring some products
    $previewArtisansWhooseYouAreSponsoringSomeProducts = obtainPreviewArtisansWhooseThisArtisanIsSponsoringSomeProducts($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($previewArtisansWhooseYouAreSponsoringSomeProducts as &$singleArtisanPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Number of products you are sponsoring of this artisan").": ".$singleArtisanPreview["numberProductsIsSponsoring"]);
    }
    endCardGrid();
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
    //Cooperating artisans
    addSubtopicIndex("cooperatingArtisans");
    addTitle(translate("Cooperating artisans"));
    addParagraph(translate("Here there are the artisans with which you are sponsoring some products each others"));
    //Show artisans with which you are sponsoring some products each others
    $artisansWithWhichYouAreSponsoringSomeProductsEachOthers = obtainPreviewArtisansWithWhichYouAreSponsoringSomeProductsEachOthers($_SESSION["userId"]);
    startCardGrid();
    $foundAResult = false;
    foreach($artisansWithWhichYouAreSponsoringSomeProductsEachOthers as &$singleArtisanPreview){
      $foundAResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
    }
    endCardGrid();
    //In case of no result
    if($foundAResult == false){
      addParagraph(translate("No result"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
