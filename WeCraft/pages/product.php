<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a product by id (the id is sent as a get)
  //The atisan owner of this product has more options on the page such as add image, add tags, change quantity, edit the product
  doInitialScripts();
  addScriptAddThisPageToCronology();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Product"),"cookieBack");
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
        addApiActionViaJsLink("-",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=dec&productId=".urlencode($_GET["id"])."&token=".urlencode($_SESSION['csrftoken']),"dec","updateQuantityValue");
        endCol();
        addColMiniSpacer();
        startCol();
        addApiActionViaJsLink("+",WeCraftBaseUrl."api/changeQuantityOfAProduct.php?kind=inc&productId=".urlencode($_GET["id"])."&token=".urlencode($_SESSION['csrftoken']),"inc","updateQuantityValue");
        endCol();
        endRow();
        ?>
          <script>
            //When the artisan click on button + or - to change the quantity, the quantity is changed without exiting from the page via an api
            //It is also changed the text of the quantity via innerHTML
            function updateQuantityValue(){
              const quantity = document.getElementById('quantity');
              let requestUrl = "<?= WeCraftBaseUrl ?>api/getQuantityOfThisProduct.php?productId=" + encodeURIComponent(<?= $_GET["id"] ?>);
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
        startParagraph();
        insertHtmlWritten(translate("Tags").":");
        for($i=0;$i<$numberOfTags;$i++){
          insertHtmlWritten(" ");
          insertALink($tags[$i]['tag'],"./tag.php?tag=".urlencode($tags[$i]['tag']));
          $firstTimeTagWritten = false;
        }
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
      addParagraph(translate("Number of unit sold").": ".numberOfUnitsSoldOfThisProduct($_GET["id"]));
      //Info about the artisan owner of this product
      addParagraph(translate("Product created by this artisan").":");
      $fileImageToVisualizeArtisan = genericUserImage;
      if(isset($artisanInfosUser['icon']) && ($artisanInfosUser['icon'] != null)){
        $fileImageToVisualizeArtisan = blobToFile($artisanInfosUser["iconExtension"],$artisanInfosUser['icon']);
      }
      $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($productInfos["artisan"]);
      addACard("./artisan.php?id=".urlencode($productInfos["artisan"]),$fileImageToVisualizeArtisan,htmlentities($artisanInfosUser["name"]." ".$artisanInfosUser["surname"]),htmlentities($artisanInfosArtisan["shopName"]),translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
      //Carousel with images of this product
      addCarouselImagesOfThisProduct($_GET["id"]);
      //Add to shopping cart this product (if you are a customer) (in case the available quantity of this product is 0, this button is not shown)
      if($kindOfTheAccountInUse == "Customer" && $productInfos["quantity"] > 0){
        addButtonLink(translate("Add to shopping cart"),"./addToShoppingCart.php?id=".urlencode($_GET["id"]));
        $quantityOfThisProductInShoppingCartByThisUser = getQuantityOfThisProductInShoppingCartByThisUser($_GET["id"],$_SESSION["userId"]);
        $AddedToShoppingCartWritten = translate("Added to shopping cart").": ".$quantityOfThisProductInShoppingCartByThisUser;
        addParagraph($AddedToShoppingCartWritten);
      }
      //This product is available also from theese artisans
      $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
      if($numberOtherArtisansWhoAreSellingThisExchangeProduct > 0){
        addParagraph(translate("This product is sold also from the shop of theese artisans")." (".$numberOtherArtisansWhoAreSellingThisExchangeProduct."):");
        $previewOtherArtisansWhoAreSellingThisExchangeProduct = obtainPreviewOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
        startCardGrid();
        foreach($previewOtherArtisansWhoAreSellingThisExchangeProduct as &$singleArtisanPreview){
          $fileImageToVisualize = genericUserImage;
          if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
          }
          $text2 = translate("Quantity available from the store of this artisan").": ".$singleArtisanPreview["quantity"];
          if($singleArtisanPreview["quantity"] == "0"){
            $text2 = translate("Not available from this artisan");
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),$text2);
        }
        endCardGrid();
      }
      //This product is suggested also by theese artisans
      $numberOtherArtisansWhoAreSponsoringThisProduct = obtainNumberOtherArtisansWhoAreSponsoringThisProduct($_GET["id"]);
      if($numberOtherArtisansWhoAreSponsoringThisProduct > 0){
        addParagraph(translate("This product is suggested also by theese artisans")." (".$numberOtherArtisansWhoAreSponsoringThisProduct."):");
        $previewOtherArtisansWhoAreSponsoringThisProduct = obtainPreviewOtherArtisansWhoAreSponsoringThisProduct($_GET["id"]);
        startCardGrid();
        foreach($previewOtherArtisansWhoAreSponsoringThisProduct as &$singleArtisanPreview){
          $fileImageToVisualize = genericUserImage;
          if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
        }
        endCardGrid();
      }
      //Edit product (you can edit the product if you are the owner)
      if($_SESSION["userId"] == $productInfos["artisan"]){
        addButtonLink(translate("Edit product general info"),"./editProductGeneralInfo.php?id=".urlencode($_GET["id"]));
        addButtonLink(translate("Edit product category"),"./editProductCategory.php?id=".urlencode($_GET["id"]));
        if($fileImageToVisualizeProduct != genericProductImage){
          addButtonLink(translate("Delete product icon"),"./deleteProductIcon.php?id=".urlencode($_GET["id"]));
        }
        addButtonLink(translate("Edit product icon"),"./editProductIcon.php?id=".urlencode($_GET["id"]));
        addButtonLink(translate("Add images to this product"),"./addImagesToThisProduct.php?id=".urlencode($_GET["id"]));
        $numberOfImages = getNumberImagesOfThisProduct($_GET["id"]);
        if($numberOfImages > 0){
          addButtonLink(translate("Remove images to this product"),"removeImagesToThisProduct.php?id=".urlencode($_GET["id"]));
        }
        addButtonLink(translate("Add tags to this product"),"./addTagsToThisProduct.php?id=".urlencode($_GET["id"]));
        if($numberOfTags > 0){
          addButtonLink(translate("Remove tags to this product"),"./removeTagsToThisProduct.php?id=".urlencode($_GET["id"]));
        }
      } else if($kindOfTheAccountInUse == "Artisan"){//If you are not the owner butyou are another artisan, here there are some functions about the cooperation
        //Cooperation for visibility and marketing
        $areYouSponsoringThisProduct = isThisArtisanSponsoringThisProduct($_SESSION["userId"],$_GET["id"]);
        $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
        addButtonOnOffApiActionViaJsLink($areYouSponsoringThisProduct,translate("Sponsor this product"),WeCraftBaseUrl."api/changeIfYouAreSponsoringThisProduct.php?artisan=".urlencode($_SESSION["userId"])."&product=".urlencode($_GET["id"])."&token=".urlencode($_SESSION['csrftoken']),"sponsorAProduct");
        //Exchange products
        $areYouSellingThisExchangeProduct = isThisArtisanSellingThisExchangeProduct($_SESSION["userId"],$_GET["id"]);
        if($areYouSellingThisExchangeProduct){
          addParagraph(translate("You are selling this exchange product"));
          addButtonLink(translate("Stop selling this exchange product"),"./stopSellingThisExchangeProduct.php?id=".urlencode($_GET["id"]));
          $quantityOfThisExchangeProduct = obtainQuantityExchangeProduct($_SESSION["userId"],$_GET["id"]);
          addParagraph(translate("Quantity of this product available in your store").": ".$quantityOfThisExchangeProduct,"exchangeQuantity");
          startRow();
          startCol();
          addApiActionViaJsLink("-",WeCraftBaseUrl."api/changeQuantityOfAnExchangeProduct.php?kind=dec&productId=".urlencode($_GET["id"])."&token=".urlencode($_SESSION['csrftoken']),"dec","updateExchangeQuantityValue");
          endCol();
          addColMiniSpacer();
          startCol();
          addApiActionViaJsLink("+",WeCraftBaseUrl."api/changeQuantityOfAnExchangeProduct.php?kind=inc&productId=".urlencode($_GET["id"])."&token=".urlencode($_SESSION['csrftoken']),"inc","updateExchangeQuantityValue");
          endCol();
          endRow();
          ?>
            <script>
              //When the artisan who is selling this exchange product click on button + or - to change the quantity of this exchange product available in his store,
              //the quantity is changed without exiting from the page via an api
              //It is also changed the text of the quantity via innerHTML
              function updateExchangeQuantityValue(){
                const exchangeQuantity = document.getElementById('exchangeQuantity');
                let requestUrl = "<?= WeCraftBaseUrl ?>api/getExchangeQuantityOfThisProduct.php?artisan=" + encodeURIComponent(<?= $_SESSION["userId"] ?>) + "&product=" + encodeURIComponent(<?= $_GET["id"] ?>);
                let request = new XMLHttpRequest();
                request.open("GET", requestUrl);
                request.responseType = "json";
                request.send();
                request.onload = function(){
                  const result = request.response;
                  let quantityOfThisProduct = result[0].quantity;
                  exchangeQuantity.innerHTML = "<?= translate("Quantity of this product available in your store").": " ?>"+quantityOfThisProduct;
                }
              }
            </script>
          <?php
          addButtonLink(translate("Change quantity"),"./startSellingThisExchangeProduct.php?id=".urlencode($_GET["id"]));
        } else {
          addButtonLink(translate("Start selling this exchange product"),"./startSellingThisExchangeProduct.php?id=".urlencode($_GET["id"]));
        }
        //In case you both sponsor and sell this product
        if($areYouSponsoringThisProduct && $areYouSellingThisExchangeProduct){
          addParagraph(translate("In case you both sponsor and sell this product of another artisan on your artisan page it will be shown only as a product you sell"));
        }
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
