<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Start selling this exchange product (sell a product of onother artisan in your store) by the id of the product
  doInitialScripts();

  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for starting to sell this exchange product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Sell exchange product"),"./product.php?id=".urlencode($insertedProductId));
    $insertedQuantity = $_POST['insertedQuantity'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedQuantity == ""){
      addParagraph(translate("You have missed to insert the quantity"));
    } else if(strlen($insertedQuantity) > 5){
      addParagraph(translate("The quantity is too big"));
    } else if(!isValidQuantity($insertedQuantity)){
      addParagraph(translate("The quantity is not a number"));
    } else {
      //Check that this product exists and check that the user is an artisan which is not the owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] != $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Add this exchange product or change the quantity
            if(isThisArtisanSellingThisExchangeProduct($_SESSION["userId"],$insertedProductId)){
              updateQuantityExchangeProduct($_SESSION["userId"],$insertedProductId,$insertedQuantity);
            } else {
              startSellingThisExchangeProduct($_SESSION["userId"],$insertedProductId,$insertedQuantity);
            }
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
      upperPartOfThePage(translate("Sell exchange product"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to not be the owner of this product and to be an artisan
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] != $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Content of this page
            //Title Sell this product of another artisan in your store
            addTitle(translate("Sell this product of another artisan in your store"));
            $productInfos = obtainProductInfos($_GET["id"]);
            addParagraph(translate("Product").": ".$productInfos["name"]);
            //Form to insert data for start selling this exchange product or to change the quantity
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addShortTextField(translate("Quantity"),"insertedQuantity",5);
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const insertedQuantity = document.getElementById('insertedQuantity');

                //Load form fields starting values (only in case of change quantity)
                <?php
                  if(isThisArtisanSellingThisExchangeProduct($_SESSION["userId"],$_GET["id"])){
                    ?>
                      insertedQuantity.value = "<?= obtainQuantityExchangeProduct($_SESSION["userId"],$_GET["id"]) ?>";
                    <?php
                  }
                ?>
    
                function isValidQuantity(quantity){
                  const quantityRegex = /^[0-9]+$/;
                  return quantityRegex.test(quantity);
                }
    
                //prevent sending form with errors
                form.onsubmit = function(e){
                  if(insertedQuantity.value === ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the quantity") ?>");
                  } else if(!isValidQuantity(insertedQuantity.value)){
                    e.preventDefault();
                    alert("<?= translate("The quantity is not a number") ?>");
                  }
                }
              </script>
            <?php
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
      upperPartOfThePage(translate("Sell exchange product"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
