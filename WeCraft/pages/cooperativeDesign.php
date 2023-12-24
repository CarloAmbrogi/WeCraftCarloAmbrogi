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
    //Show the products for whitch you are collaborating in a cooperative design
    addButtonOnOffShowHide(translate("Visualise only product of which you are the owner"),"youArentTheOwner");
    $productsForWhitchYouAreCollaborating = obtainProductsPreviewCooperativeDesign($_SESSION["userId"]);
    startCardGrid();
    foreach($productsForWhitchYouAreCollaborating as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Owner").": ".$singleProductPreview["ownerName"]." ".$singleProductPreview["ownerSurname"];
      $text2 = translate("Number of collaborators").": ".$singleProductPreview["numberOfCollaborators"];
      if($_SESSION["userId"] != $singleProductPreview["ownerId"]){
        startDivShowHideMultiple("youArentTheOwner");
      }
      addACardForTheGrid("./cooperativeDesignProduct.php?id=".urlencode($singleProductPreview["productId"]),$fileImageToVisualize,$singleProductPreview["productName"],$text1,$text2);
      if($_SESSION["userId"] != $singleProductPreview["ownerId"]){
        endDivShowHideMultiple();
      }
    }
    endCardGrid();
    addScriptShowHideMultiple("youArentTheOwner",true);
    forceThisPageReloadWhenBrowserBackButton();
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
