<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Category
  //See products of the category from a get param
  doInitialScripts();
  addScriptAddThisPageToCronology();
  upperPartOfThePage(translate("Category"),"./index.php");
  //Content of the page
  if(isset($_GET["cat"])){
    $categoryString = translate($_GET["cat"]);
    if(substr($categoryString,0,6) == "MISSED"){
      $categoryString = translate("Inexistent category");
    } else {
      $categoryString = strtolower($categoryString);
      $categoryString = translate("Category")." ".$categoryString;
    }
    addTitle($categoryString);
    //Show results
    $SearchPreviewProducts = obtainPreviewProductsWithThisCategory($_GET["cat"]);
    startCardGrid();
    $foundAtLeastOneResult = false;
    foreach($SearchPreviewProducts as &$singleProductPreview){
      $foundAtLeastOneResult = true;
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
    }
    endCardGrid();
    //In case of no result
    if($foundAtLeastOneResult == false){
      addParagraphUnsafe("<br>".translate("No product of this category"));
    }
  } else {
    translate("You have missed to specify the category");
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
