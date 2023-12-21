<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Recent orders
  //See the content of a specific recent order
  //This page is visible only to customers
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Recent orders"),"./recentOrders.php");
  if($kindOfTheAccountInUse == "Customer"){
    if(isset($_GET["id"])){
      $doesThisRecentOrederExists = doesThisRecentOrederExists($_SESSION["userId"],$_GET["id"]);
      if($doesThisRecentOrederExists){
        $recentOrderGeneralInfos = recentOrderGeneralInfos($_SESSION["userId"],$_GET["id"]);
        addParagraph($recentOrderGeneralInfos["timestamp"]);
        addParagraph(translate("Total cost").": ".floatToPrice($recentOrderGeneralInfos["totalCost"]));
        addParagraphUnsafe(translate("Number of products").": ".htmlentities($recentOrderGeneralInfos["numberOfProducts"])."<br>".translate("Number of different products").": ".htmlentities($recentOrderGeneralInfos["numberOfDifferentProducts"]));
        addParagraph($recentOrderGeneralInfos["address"]);
        startCardGrid();
        $contentThisRecentOrder = obtainRecentOrder($_SESSION["userId"],$_GET["id"]);
        $lastProductId = -1;
        foreach($contentThisRecentOrder as &$singlePieceContentThisRecentOrder){
          $thisProductId = $singlePieceContentThisRecentOrder["product"];
          $singleItemCost = $singlePieceContentThisRecentOrder["singleItemCost"];
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
          $thisArtisan = $singlePieceContentThisRecentOrder["artisan"];
          $quantityFromThisArtisan = $singlePieceContentThisRecentOrder["quantity"];
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
        addParagraph(translate("This recent order doesnt exists"));
      }
    } else {
      addParagraph(translate("You have missed to specify the id of this recent order"));
    }
  } else {
    addParagraph(translate("This page is visible only to customers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
