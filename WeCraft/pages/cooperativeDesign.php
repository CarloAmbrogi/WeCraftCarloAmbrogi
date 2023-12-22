<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperative design
  doInitialScripts();
  addScriptAddThisPageToCronology();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan" && $kindOfTheAccountInUse != "Designer"){
    //This page is visible only to artisans and designers
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperative design"),"./cooperate.php");
    addTitle(translate("Cooperation with others to realize a product"));
    addParagraph(translate("Here will be shown the products for witch you are collaborating with other artisans and designers"));
    //Show the products for witch you are collaborating in a cooperative design

    //AAAAAAAAA
    /*
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
    */

  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
