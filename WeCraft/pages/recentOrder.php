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
  upperPartOfThePage(translate("Recent orders"),"./myWeCraft.php");
  if($kindOfTheAccountInUse == "Customer"){
    if(isset($_GET["id"])){
      $doesThisRecentOrederExists = doesThisRecentOrederExists($_SESSION["userId"],$_GET["id"]);
      if($doesThisRecentOrederExists){
        $recentOrderGeneralInfos = recentOrderGeneralInfos($_SESSION["userId"],$_GET["id"]);
        addParagraph($recentOrderGeneralInfos["timestamp"]);
        addParagraph(translate("Total cost").": ".floatToPrice($recentOrderGeneralInfos["totalCost"]));
        addParagraphUnsafe(translate("Number of products").": ".htmlentities($recentOrderGeneralInfos["numberOfProducts"])."<br>".translate("Number of different products").": ".htmlentities($recentOrderGeneralInfos["numberOfDifferentProducts"]));
        addParagraph($recentOrderGeneralInfos["address"]);
        $contentOfThisRecentOrder = obtainContentRecentOrder($_SESSION["userId"],$_GET["id"]);
        foreach($contentOfThisRecentOrder as &$singleProductPreview){
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $text1 = translate("From")." ".$singleProductPreview["shopName"]." (".$singleProductPreview["name"]." ".$singleProductPreview["surname"].")";
          $subtotal = $singleProductPreview["price"] * $singleProductPreview["quantity"];
          $text2 = translate("Quantity").": ".$singleProductPreview["quantity"]." x ".translate("today price").": ".floatToPrice($singleProductPreview["price"])." = ".floatToPrice($subtotal);
          addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["product"]),$fileImageToVisualize,htmlentities($singleProductPreview["productName"]),htmlentities($text1),htmlentities($text2));
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
