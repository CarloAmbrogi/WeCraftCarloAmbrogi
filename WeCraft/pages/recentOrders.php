<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Recent orders
  //See the recent orders list
  //This page is visible only to customers
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Recent orders"),"./myWeCraft.php");
  if($kindOfTheAccountInUse == "Customer"){
    $numberOfRecentOrdersOfThisUser = numberOfRecentOrdersOfThisUser($_SESSION["userId"]);
    if($numberOfRecentOrdersOfThisUser > 0){
      addParagraph(translate("Number of recent orders").": ".$numberOfRecentOrdersOfThisUser);
      $previewRecentOrdersOfThisUser = obtainPreviewRecentOrdersOfThisUser($_SESSION["userId"]);
      startCardGrid();
      foreach($previewRecentOrdersOfThisUser as &$singleRecentOrder){
        $fileImageToVisualize = orderIcon;
        $title = htmlentities($singleRecentOrder["timestamp"])."<br>".htmlentities(floatToPrice($singleRecentOrder["totalCost"]));
        $text1 = translate("Number of products").": ".htmlentities($singleRecentOrder["numberOfProducts"])."<br>".translate("Number of different products").": ".htmlentities($singleRecentOrder["numberOfDifferentProducts"]);
        $text2 = $singleRecentOrder["address"];
        addACardForTheGrid("./recentOrder.php?id=".urlencode($singleRecentOrder["id"]),$fileImageToVisualize,$title,$text1,htmlentities($text2));
      }
      endCardGrid();
    } else {
      addParagraph(translate("You have no recent order"));
    }
  } else {
    addParagraph(translate("This page is visible only to customers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
