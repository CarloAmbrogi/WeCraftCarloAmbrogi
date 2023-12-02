<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a product by id (the id is sent as a get)
  //The atisan owner of this product has more options on the page such as add image, add tags, change quantity, edit the product
  doInitialScripts();
  upperPartOfThePage(translate("Product"),"jsBack");//AAAAAAAA don't use jsback but the page that sent you here adds a get to let you know if you go back where (without this get the button will not be shown)
  if(isset($_GET["id"])){
    if(doesThisProductExists($_GET["id"])){
      $productInfos = obtainProductInfos($_GET["id"]);
      $artisanInfosUser = obtainUserInfos($productInfos["artisan"]);
      $artisanInfosArtisan = obtainArtisanInfos($productInfos["artisan"]);
      startRow();
      startCol();
      $fileImageToVisualize = genericProductImage;
      if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
        $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
      }
      addLateralImageLow($fileImageToVisualize,$nameAndSurname);
      endCol();
      startCol();
      addTitle($productInfos["name"]);
      addParagraph(translate("Category").": ".translate($productInfos["category"]));
      addParagraph(translate("Price").": ".floatToPrice($productInfos["price"]));
      $textQuantity = translate("Quantity available").": ".$productInfos["quantity"];
      if($productInfos["quantity"] == "0"){
        $textQuantity = translate("Not available");
      }
      addParagraph($textQuantity);
      if($_SESSION["userId"] == $productInfos["artisan"]){
        startRow();
        startCol();
        addButtonLink("+","AAAAAAAAAAA");
        endCol();
        startCol();
        endCol();
        startCol();
        addButtonLink("-","AAAAAAAAAAA");
        endCol();
        endRow();
      }
      //AAAAAAAAAAA tags
      endCol();
      endRow();
      addParagraph($productInfos["description"]);
      addParagraph(translate("Added when").": ".$productInfos["added"]);
      $lastSellString = $productInfos["lastSell"];
      if($lastSellString == null || $lastSellString == ""){
        $lastSellString = translate("never sold");
      }
      addParagraph(translate("Last sell").": ".$lastSellString);
      addParagraph(translate("Product created by this artisan").":");
      $fileImageToVisualize = genericUserImage;
      if(isset($artisanInfosUser['icon']) && ($artisanInfosUser['icon'] != null)){
        $fileImageToVisualize = blobToFile($artisanInfosUser["iconExtension"],$artisanInfosUser['icon']);
      }
      addACard("./artisan.php?id=".$productInfos["artisan"],$fileImageToVisualize,$artisanInfosUser["name"]." ".$artisanInfosUser["surname"],$artisanInfosArtisan["shopName"],"");
      //AAAAAAAAAAA images carousel
      //AAAAAAAAAAA edit product

    } else {
      addParagraph(translate("This product doesnt exists"));
    }
  } else {
    //You have missed to specify the get param id of the product
    addParagraph(translate("You have missed to specify the get param id of the product"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
