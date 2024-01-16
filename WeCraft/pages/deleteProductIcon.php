<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Delete product icon of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the product category of this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    $possibleCategories = categories;
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Delete the icon of this product
          deleteIconOfAProduct($insertedProductId);
          addParagraph(translate("Done"));
          //sync also on Magis
          $imageUrl = genericProductImage;
          $idOfThisProduct = $insertedProductId;
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeImageUrlMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&imageUrl=".urlencode($imageUrl)."&url=".urlencode(WeCraftBaseUrl."pages/product.php?id=".$idOfThisProduct));  
        } else {
          addParagraph(translate("You cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Title Delete the icon of this product
          addTitle(translate("Delete the icon of this product"));
          //Form to insert data to delete the icon of this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addHiddenField("insertedProductId",$_GET["id"]);
          endForm(translate("Delete product icon"));
          //End main content of this page
        } else {
          addParagraph(translate("You cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Edit product"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
