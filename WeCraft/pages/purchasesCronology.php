<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Purchases cronology
  //See the purchases cronology
  //This page is visible only to customers and other users
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Purchases cronology"),"./myWeCraft.php");
  if($kindOfTheAccountInUse != "Guest"){
    $numberOfPurchasesOfThisUser = numberPurchasesOfThisUser($_SESSION["userId"]);
    if($numberOfPurchasesOfThisUser > 0){
      addParagraph(translate("Number of purchases").": ".$numberOfPurchasesOfThisUser);
      $previewPurchasesCronologyOfThisUser = obtainPreviewPurchasesCronologyOfThisUser($_SESSION["userId"]);
      startCardGrid();
      foreach($previewPurchasesCronologyOfThisUser as &$singlePurchase){
        $fileImageToVisualize = purchaseIcon;
        $title = htmlentities($singlePurchase["timestamp"])."<br>".htmlentities(floatToPrice($singlePurchase["totalCost"]));
        $text1 = translate("Number of products").": ".htmlentities($singlePurchase["numberOfProducts"])."<br>".translate("Number of different products").": ".htmlentities($singlePurchase["numberOfDifferentProducts"]);
        $text2 = $singlePurchase["address"];
        addACardForTheGrid("./purchase.php?id=".urlencode($singlePurchase["id"]),$fileImageToVisualize,$title,$text1,htmlentities($text2));
      }
      endCardGrid();
    } else {
      addParagraph(translate("You have no purchase"));
    }
  } else {
    addParagraph(translate("You have to be logged to see this page"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
