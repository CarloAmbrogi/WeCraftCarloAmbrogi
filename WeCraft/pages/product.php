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
      $textQuantity = translate("Quantity available")." ".translate("at")." ".$artisanInfosArtisan["shopName"].": ".$productInfos["quantity"];
      if($productInfos["quantity"] == "0"){
        $textQuantity = translate("Not available");
      }
      addParagraph($textQuantity,"quantity");
      if($_SESSION["userId"] == $productInfos["artisan"]){
        //The owner of this product can change the quantity available in his shop
        startRow();
        startCol();
        addApiActionViaJsLink("+",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=inc&productId=".$_GET["id"],"inc","updateQuantityValue");
        endCol();
        addColMiniSpacer();
        startCol();
        addApiActionViaJsLink("-",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=dec&productId=".$_GET["id"],"dec","updateQuantityValue");
        endCol();
        endRow();
        ?>
          <script>
            //When the artisan click on button + or - to change the quantity, the quantity is changed without exiting from the page via an api
            //It is also changed the text of the quantity via innerHTML
            function updateQuantityValue(){
              const quantity = document.getElementById('quantity');
              let requestUrl = "<?= WeCraftBaseUrl ?>api/getQuantityOfThisProduct.php?productId=" + <?= $_GET["id"] ?>;
              let request = new XMLHttpRequest();
              request.open("GET", requestUrl);
              request.responseType = "json";
              request.send();
              request.onload = function(){
                const result = request.response;
                let quantityOfThisProduct = result[0].quantityOfThisProduct;
                quantity.innerHTML = "<?= translate("Quantity available")." ".translate("at")." ".$artisanInfosArtisan["shopName"].": " ?>"+quantityOfThisProduct;
              }
            }
          </script>
        <?php
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
      //Edit product (you can edit the product if you are the owner)
      if($_SESSION["userId"] == $productInfos["artisan"]){
        addButtonLink(translate("Edit product general info"),"./editProductGeneralInfo.php?id=".$_GET["id"]);
        addButtonLink(translate("Edit product category"),"./editProductCategory.php?id=".$_GET["id"]);
        addButtonLink(translate("Delete product icon"),"deleteProductIcon.php?id=".$_GET["id"]);
        addButtonLink(translate("Edit product icon"),"editProductIcon.php?id=".$_GET["id"]);
        addButtonLink(translate("Add images to this product"),"addImagesToThisProduct.php?id=".$_GET["id"]);
        addButtonLink(translate("Remove images to this product"),"removeImagesToThisProduct.php?id=".$_GET["id"]);
        addButtonLink(translate("Add tags to this product"),"addTagsToThisProduct.php?id=".$_GET["id"]);
        addButtonLink(translate("Remove tags to this product"),"removeTagsToThisProduct.php?id=".$_GET["id"]);
      }
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
