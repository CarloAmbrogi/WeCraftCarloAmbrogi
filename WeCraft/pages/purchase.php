<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Purchase
  //See the content of a specific purchase
  //This page is visible only to customers and other users
  doInitialScripts();
  addScriptAddThisPageToChronology();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Purchase"),"./purchasesChronology.php");
  if($kindOfTheAccountInUse != "Guest"){
    if(isset($_GET["id"])){
      $doesThisPurchaseExists = doesThisPurchaseExists($_SESSION["userId"],$_GET["id"]);
      if($doesThisPurchaseExists){
        $purchaseGeneralInfos = purchaseGeneralInfos($_SESSION["userId"],$_GET["id"]);
        addParagraph($purchaseGeneralInfos["timestamp"]);
        addParagraph(translate("Total cost").": ".floatToPrice($purchaseGeneralInfos["totalCost"]));
        addParagraphUnsafe(translate("Number of products").": ".htmlentities($purchaseGeneralInfos["numberOfProducts"])."<br>".translate("Number of different products").": ".htmlentities($purchaseGeneralInfos["numberOfDifferentProducts"]));
        addParagraph($purchaseGeneralInfos["address"]);
        startCardGrid();
        $contentThisPurchase = obtainPurchase($_SESSION["userId"],$_GET["id"]);
        $lastProductId = -1;
        foreach($contentThisPurchase as &$singlePieceContentThisPurchase){
          $thisProductId = $singlePieceContentThisPurchase["product"];
          $singleItemCost = $singlePieceContentThisPurchase["singleItemCost"];
          if($thisProductId != $lastProductId){
            endCardGrid();
            $lastProductId = $thisProductId;
            addParagraph(translate("Product").":");
            startCardGrid();
            $productInfos = obtainProductInfos($thisProductId);
            $fileImageToVisualize = genericProductImage;
            if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
              $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
            }
            $text1 = translate("Payed price per unit").": ".$singleItemCost;
            addACardForTheGrid("./product.php?id=".urlencode($thisProductId),$fileImageToVisualize,htmlentities($productInfos["name"]),htmlentities($text1),"");
            endCardGrid();
            startCardGrid();
          }
          $thisArtisan = $singlePieceContentThisPurchase["artisan"];
          $quantityFromThisArtisan = $singlePieceContentThisPurchase["quantity"];
          $userInfos = obtainUserInfos($thisArtisan);
          $artisanInfos = obtainArtisanInfos($thisArtisan);
          $fileImageToVisualize = genericUserImage;
          if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
          }
          $artisanNameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
          $artisanShopName = $artisanInfos["shopName"];
          $subTotal = $singleItemCost * $quantityFromThisArtisan;
          $text2 = translate("Took")." ".$quantityFromThisArtisan." ".translate("units from this artisan")." (".floatToPrice($subTotal).")";
          addACardForTheGrid("./artisan.php?id=".urlencode($thisArtisan),$fileImageToVisualize,htmlentities($artisanNameAndSurname),htmlentities($artisanShopName),htmlentities($text2));
        }
        endCardGrid();
      } else {
        addParagraph(translate("This purchase doesnt exists"));
      }
    } else {
      addParagraph(translate("You have missed to specify the id of this purchase"));
    }
  } else {
    addParagraph(translate("You have to be logged to see this page"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
