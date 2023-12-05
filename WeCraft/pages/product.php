<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a product by id (the id is sent as a get)
  //The atisan owner of this product has more options on the page such as add image, add tags, change quantity, edit the product
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Product"),"jsBack");//AAAAAAAA don't use jsback but the page that sent you here adds a get to let you know if you go back where (without this get the button will not be shown)
  if(isset($_GET["id"])){
    if(doesThisProductExists($_GET["id"])){
      //Real content of this page
      //General info of this product
      $productInfos = obtainProductInfos($_GET["id"]);
      $artisanInfosUser = obtainUserInfos($productInfos["artisan"]);
      $artisanInfosArtisan = obtainArtisanInfos($productInfos["artisan"]);
      startRow();
      startCol();
      $fileImageToVisualizeProduct = genericProductImage;
      if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
        $fileImageToVisualizeProduct = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
      }
      addLateralImageLow($fileImageToVisualizeProduct,$nameAndSurname);
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
        $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
        addApiActionViaJsLink("-",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=dec&productId=".$_GET["id"]."&token=".$_SESSION['csrftoken'],"dec","updateQuantityValue");
        endCol();
        addColMiniSpacer();
        startCol();
        addApiActionViaJsLink("+",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=inc&productId=".$_GET["id"]."&token=".$_SESSION['csrftoken'],"inc","updateQuantityValue");
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
      //Tags of this product
      $numberOfTags = getNumberTagsOfThisProduct($_GET["id"]);
      if($numberOfTags > 0){
        $tags = getTagsOfThisProduct($_GET["id"]);
        $tagWritten = translate("Tags").":";
        for($i=0;$i<$numberOfTags;$i++){
          $tagWritten = $tagWritten." ";
          $tagWritten = $tagWritten.$tags[$i]['tag'];
          $firstTimeTagWritten = false;
        }
        addParagraph($tagWritten);
      } else {
        addParagraph(translate("No tag"));
      }
      endCol();
      endRow();
      //Description and other information on this product
      addParagraph($productInfos["description"]);
      addParagraph(translate("Added when").": ".$productInfos["added"]);
      $lastSellString = $productInfos["lastSell"];
      if($lastSellString == null || $lastSellString == ""){
        $lastSellString = translate("never sold");
      }
      addParagraph(translate("Last sell").": ".$lastSellString);
      //Info about the artisan owner of this product
      addParagraph(translate("Product created by this artisan").":");
      $fileImageToVisualizeArtisan = genericUserImage;
      if(isset($artisanInfosUser['icon']) && ($artisanInfosUser['icon'] != null)){
        $fileImageToVisualizeArtisan = blobToFile($artisanInfosUser["iconExtension"],$artisanInfosUser['icon']);
      }
      $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($productInfos["artisan"]);
      addACard("./artisan.php?id=".$productInfos["artisan"],$fileImageToVisualizeArtisan,htmlentities($artisanInfosUser["name"]." ".$artisanInfosUser["surname"]),htmlentities($artisanInfosArtisan["shopName"]),translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
      //Carousel with images of this product
      addCarouselImagesOfThisProduct($_GET["id"]);
      //Edit product (you can edit the product if you are the owner)
      if($_SESSION["userId"] == $productInfos["artisan"]){
        addButtonLink(translate("Edit product general info"),"./editProductGeneralInfo.php?id=".$_GET["id"]);
        addButtonLink(translate("Edit product category"),"./editProductCategory.php?id=".$_GET["id"]);
        if($fileImageToVisualizeProduct != genericProductImage){
          addButtonLink(translate("Delete product icon"),"./deleteProductIcon.php?id=".$_GET["id"]);
        }
        addButtonLink(translate("Edit product icon"),"./editProductIcon.php?id=".$_GET["id"]);
        addButtonLink(translate("Add images to this product"),"./addImagesToThisProduct.php?id=".$_GET["id"]);
        $numberOfImages = getNumberImagesOfThisProduct($_GET["id"]);
        if($numberOfImages > 0){
          addButtonLink(translate("Remove images to this product"),"removeImagesToThisProduct.php?id=".$_GET["id"]);
        }
        addButtonLink(translate("Add tags to this product"),"./addTagsToThisProduct.php?id=".$_GET["id"]);
        if($numberOfTags > 0){
          addButtonLink(translate("Remove tags to this product"),"./removeTagsToThisProduct.php?id=".$_GET["id"]);
        }
      }
      //Add to shopping cart this product (if you are a customer) (in case the available quantity of this product is 0, this button is not shown)
      if($kindOfTheAccountInUse == "Customer" && $productInfos["quantity"] > 0){
        addButtonLink(translate("Add to shopping cart"),"./addToShoppingCart.php?id=".$_GET["id"]);
        $quantityOfThisProductInShoppingCartByThisUser = getQuantityOfThisProductInShoppingCartByThisUser($_GET["id"],$_SESSION["userId"]);
        $AddedToShoppingCartWritten = translate("Added to shopping cart").": ".$quantityOfThisProductInShoppingCartByThisUser;
        addParagraph($AddedToShoppingCartWritten);
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
