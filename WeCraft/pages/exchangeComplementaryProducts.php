<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Exchange complementary products
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperate"),"./cooperate.php");
    addTitle(translate("Exchange complementary products"));
    addParagraph(translate("Here will be shown the products of other artisans you are selling in your physical shop"));
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
      addACardForTheGrid("./product.php?id=".$singleProductPreview["id"],$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
    }
    endCardGrid();
    addParagraph(translate("If you receive from an artisan an his product you can start sell it in your physical shop and you can notify this on WeCraft from the page of that product"));
    addParagraph(translate("Here there are some suggestions of possible artisans who sell complemetary products to yours").":");
    //Show these suggested artisans
    
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
