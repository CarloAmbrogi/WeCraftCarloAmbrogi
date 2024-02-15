<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Shopping cart
  //This page is visible only to customers and other users
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Shopping cart"),"");
  if($kindOfTheAccountInUse != "Guest"){
    $numberOfItemsInTheShoppingCartOfThisUser = numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]);
    if($numberOfItemsInTheShoppingCartOfThisUser > 0){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Receive post request for sending the order
        $insertedAddress = trim($_POST['insertedAddress']);
        $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
        //Check on the input form data
        if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
          addParagraph(translate("Error of the csrf token"));
        } else if($insertedAddress == ""){
          addParagraph(translate("You have missed to insert the address"));
        } else if(strlen($insertedAddress) > 49){
          addParagraph(translate("The address is too long"));
        } else if(getNumberOfViolatingItemsQ($_SESSION["userId"]) > 0){
          addParagraph(translate("You cant proceed with the order because one or more products of this oreder is not any more available with your selected quantity"));
        } else {
          //The order is sent and the payment is done
          //Move the current shopping cart in the purchases chronology
          moveCurrentShoppingCartOfThisUserPurchasesChronology($_SESSION["userId"],$insertedAddress);
          //Update data last sell
          updateDataLastSellBasedOnShoppingCartOfThisUser($_SESSION["userId"]);
          //Notify the artisans about this sell (remember also about the percentage resell)
          notifyArtisansSellContentShoppingCartOfThisUser($_SESSION["userId"]);
          //Update the remaining quantity of the products
          updateRemainingQuantityOfTheProductsBasedOnShoppingCartOfThisUser($_SESSION["userId"]);
          //Empty the current shopping cart
          emptyTheShoppingCartOfThisUser($_SESSION["userId"]);
          //Done
          addParagraph(translate("The order has been sent"));
        }
      } else {
        //Normal content of this page
        addParagraph(translate("Number of items in the shopping cart").": ".$numberOfItemsInTheShoppingCartOfThisUser);
        addButtonLink(translate("Empty the shopping cart"),"./emptyTheShoppingCart.php");
        //Items in the shopping cart
        $previewShoppingCartOfThisUser = obtainPreviewShoppingCartOfThisUser($_SESSION["userId"]);
        startCardGrid();
        $TotalPrice = 0.00;
        foreach($previewShoppingCartOfThisUser as &$singleProductPreview){
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $text1 = translate("From")." ".$singleProductPreview["shopName"]." (".$singleProductPreview["name"]." ".$singleProductPreview["surname"].")";
          $subtotal = $singleProductPreview["price"] * $singleProductPreview["quantity"];
          $TotalPrice = $TotalPrice + $subtotal;
          $text2 = translate("Quantity").": ".$singleProductPreview["quantity"]." x ".translate("price").": ".floatToPrice($singleProductPreview["price"])." = ".floatToPrice($subtotal);
          addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["product"]),$fileImageToVisualize,htmlentities($singleProductPreview["productName"]),htmlentities($text1),htmlentities($text2));
        }
        endCardGrid();
        //TotalPrice
        addParagraph(translate("Total price").": ".floatToPrice($TotalPrice));
        //Form for shipping
        startForm1();
        startForm2($_SERVER['PHP_SELF']);
        addShortTextField(translate("Address"),"insertedAddress",49);
        endForm(translate("Pay")." ".floatToPrice($TotalPrice)." ".translate("and send order"));
        ?>
          <script>
            //Your user id
            const userId = "<?= $_SESSION["userId"] ?>";

            //form inserted parameters
            const form = document.querySelector('form');
            const insertedAddress = document.getElementById('insertedAddress');

            let shoppingCartViolatingItemsCheck = false;

            //prevent sending form with errors
            form.onsubmit = function(e){
              if(insertedAddress.value.trim() == ""){
                e.preventDefault();
                alert("<?= translate("You have missed to insert the address") ?>");
              } else if(!shoppingCartViolatingItemsCheck) {
                //prevent sending form if the there is an item violating the max quantity available
                e.preventDefault();
                let requestUrl = "<?= WeCraftBaseUrl ?>api/numberOfViolatingItemsQ.php?userId=" + encodeURIComponent(userId);
                let request = new XMLHttpRequest();
                request.open("GET", requestUrl);
                request.responseType = "json";
                request.send();
                request.onload = function(){
                  const result = request.response;
                  if(result[0].numberOfViolatingItemsQ == 0){
                    shoppingCartViolatingItemsCheck = true;
                    $("#submit").unbind('click').click();
                  } else {
                    alert("<?= translate("You cant proceed with the order because one or more products of this oreder is not any more available with your selected quantity") ?>");
                  }
                }
              }
            }
          </script>
        <?php
      }
    } else {
      addParagraph(translate("You have no item in the shopping cart"));
    }
  } else {
    addParagraph(translate("You have to be logged to see this page"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
