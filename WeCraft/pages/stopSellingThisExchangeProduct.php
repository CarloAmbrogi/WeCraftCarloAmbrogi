<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Stop selling this exchange product (stop selling a product of onother artisan in your store) by the id of the product
  doInitialScripts();

  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for stopping to sell this exchange product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Stop selling exchange product"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is an artisan which is not the owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] != $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Remove this exchange product
            stopSellingThisExchangeProduct($_SESSION["userId"],$insertedProductId);
            addParagraph(translate("Done"));
          } else {
            addParagraph(translate("You are not an artisan"));
          }
        } else {
          addParagraph(translate("This is a your product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Stop selling exchange product"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to not be the owner of this product and to be an artisan
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] != $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Content of this page
            //Title Stop selling this product of another artisan in your store
            addTitle(translate("Stop selling this product of another artisan in your store"));
            $productInfos = obtainProductInfos($_GET["id"]);
            addParagraph(translate("Product").": ".$productInfos["name"]);
            //Form to insert data for stop selling this exchange product or to change the quantity
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Stop selling this exchange product"));
            //End main content of this page
          } else {
            addParagraph(translate("You are not an artisan"));
          }
        } else {
          addParagraph(translate("This is a your product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Stop selling exchange product"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
