<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Home page of WeCraft
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Welcome to WeCraft"),"");
  addScriptAddThisPageToChronology();
  //if you are logged in, you are redirect to your starting page (according if you are a customer or an artisan or a designer)
  // Title Welcome to WeCraft
  addTitle(translate("Welcome to WeCraft"));
  if($kindOfTheAccountInUse == "Guest"){
    addButtonLink(translate("Go to register log in page"),"./account.php");
  } else {
    $userInfos = obtainUserInfos($_SESSION["userId"]);
    $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
    addParagraph(translate("Welcome")." ".$nameAndSurname);
  }
  //Content of the home page
  addParagraphUnsafe(text("introduction"));
  //Categories buttons
  addTitle(translate("Categories"));
  startCardGrid();
  addACardForTheGrid("./category.php?cat=".urlencode("Jewerly"),WeCraftBaseUrl."Icons/JewerlyIcon.png",translate("Jewerly"),"","");
  addACardForTheGrid("./category.php?cat=".urlencode("Home decoration"),WeCraftBaseUrl."Icons/HomeDecorationIcon.png",translate("Home decoration"),"","");
  addACardForTheGrid("./category.php?cat=".urlencode("Pottery"),WeCraftBaseUrl."Icons/PotteryIcon.png",translate("Pottery"),"","");
  addACardForTheGrid("./category.php?cat=".urlencode("Teppiches"),WeCraftBaseUrl."Icons/TeppichesIcon.png",translate("Teppiches"),"","");
  addACardForTheGrid("./category.php?cat=".urlencode("Bedware Bathroomware"),WeCraftBaseUrl."Icons/BedwareBathroomwareIcon.png",translate("Bedware Bathroomware"),"","");
  addACardForTheGrid("./category.php?cat=".urlencode("Artisan craft"),WeCraftBaseUrl."Icons/ArtisanCraftIcon.png",translate("Artisan craft"),"","");
  endCardGrid();
  //Some products
  addTitle(translate("New products"));
  $productsPreview = obtainProductsPreviewNewProducts();
  startCardGrid();
  $addedAtListOneProduct = false;
  foreach($productsPreview as &$singleProductPreview){
    $addedAtListOneProduct = true;
    $fileImageToVisualize = genericProductImage;
    if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
    }
    $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
    $text2 = translate("Added when").": ".$singleProductPreview["added"];
    addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
  }
  endCardGrid();
  if($addedAtListOneProduct == false){
    addParagraph(translate("No product"));
  }
  addTitle(translate("Most sold products in last period"));
  $productsPreview = obtainMostSoldProductsPreviewInLastPeriod();
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
    addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
  }
  endCardGrid();
  if($addedAtListOneProduct == false){
    addParagraph(translate("No product"));
  }
  //End of the page
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
