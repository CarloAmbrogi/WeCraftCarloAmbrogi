<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Tag
  //This page is when you click on a tag, to see the products with that tag
  doInitialScripts();
  upperPartOfThePage(translate("Tag"),"jsBack");//AAAAAAAA don't use jsback but the page that sent you here adds a get to let you know if you go back to where
  addTitle("#".$_GET["tag"]);
  addButtonOnOffShowHide(translate("Show hide not products not available from the owner"),"notAvailableProduct");
  //Products with this tag
  addTitle(translate("New products with this tag"));
  $productsPreviewWithThisTag = obtainProductsPreviewWithThisTag($_GET["tag"]);
  startCardGrid();
  $addedAtListOneProduct = false;
  foreach($productsPreviewWithThisTag as &$singleProductPreview){
    $addedAtListOneProduct = true;
    $fileImageToVisualize = genericProductImage;
    if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
    }
    $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
    $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"];
    $isAvailable = true;
    if($singleProductPreview["quantity"] == "0"){
      $isAvailable = false;
    }
    if(!$isAvailable){
      startDivShowHideMultiple("notAvailableProduct");
    }
    addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    if(!$isAvailable){
      endDivShowHideMultiple();
    }
  }
  endCardGrid();
  if($addedAtListOneProduct == false){
    addParagraph(translate("No product with this tag"));
  }
  //Most sold product with this tag
  addTitle(translate("Most sold product with this tag"));
  $productsPreview = obtainMostSoldProductsWithThisTag($_GET["tag"]);
  startCardGrid();
  $addedAtListOneProduct = false;
  foreach($productsPreview as &$singleProductPreview){
    $addedAtListOneProduct = true;
    $fileImageToVisualize = genericProductImage;
    if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
    }
    $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
    $text2 = translate("Number of unit sold").": ".$singleProductPreview["numSells"];
    $isAvailable = true;
    if($singleProductPreview["quantity"] == "0"){
      $isAvailable = false;
    }
    if(!$isAvailable){
      startDivShowHideMultiple("notAvailableProduct");
    }
    addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    if(!$isAvailable){
      endDivShowHideMultiple();
    }
  }
  endCardGrid();
  if($addedAtListOneProduct == false){
    addParagraph(translate("No product"));
  }
  //Manage visibility products quantity 0
  addScriptShowHideMultiple("notAvailableProduct");
  forceThisPageReloadWhenBrowserBackButton();
  
  //End of the page
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
