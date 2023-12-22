<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit the percentage resell of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the percentage resell of this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit percentage resell"),"./product.php?id=".urlencode($insertedProductId));
    $insertedPercentageResell = $_POST['insertedPercentageResell'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedPercentageResell == ""){
      addParagraph(translate("You have missed to insert the percentage resell"));
    } else if(strlen($insertedPercentageResell) > 5){
      addParagraph(translate("The insert the percentage resell is too long"));
    } else if(!isValidPercentage($insertedPercentageResell)){
      addParagraph(translate("The inserted the percentage resell is not valid"));
    } else if($insertedPercentageResell < 0.0 || $insertedPercentageResell > 100.0){
      addParagraph(translate("The percentage is not between z and h"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check if is possible to change the percentage resell
          $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($insertedProductId);
          if($numberOtherArtisansWhoAreSellingThisExchangeProduct == 0){
            //Edit the the percentage resell of this product
            setPercentageResellOfThisProduct($insertedPercentageResell,$insertedProductId);
            addParagraph(translate("The new percentage resell is set"));
          } else {
            addParagraph(translate("You cant change the percentage resell because other artisans are now selling this your product"));
          }
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
      upperPartOfThePage(translate("Edit percentage resell"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Current percentage resell
          $isThePercentageResellSetted = isThisProductReadyToBeExchanged($_GET["id"]);
          if(!$isThePercentageResellSetted){
            addParagraph(translate("Currently the percentage resell is not set for this product"));
            addParagraph(translate("Setting this percentage this product will be ready to be sold from other artisans and this percentage represents the part whitch will return back to you"));
          } else {
            $percentageResell = percentageResellOfThisProduct($_GET["id"]);
            addParagraph(translate("The current percentage resell for this product is").": ".$percentageResell."%");
          }
          //Check if it's possible to set a percentage resell
          $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
          if($numberOtherArtisansWhoAreSellingThisExchangeProduct == 0){
            //Form to insert data to set a percentage resell for this product
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addShortTextField(translate("Percentage resell"),"insertedPercentageResell",5);
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const insertedPercentageResell = document.getElementById('insertedPercentageResell');

                function isValidFloatNumber(number){
                  const floatNumberRegex = /^[0-9]+\.?[0-9]*$/;
                  return floatNumberRegex.test(number);
                }

                function isValidQuantity(quantity){
                  const quantityRegex = /^[0-9]+$/;
                  return quantityRegex.test(quantity);
                }

                function isValidPercentage(percentage){
                  if(isValidFloatNumber(percentage)){
                    return true;
                  }
                  return isValidQuantity(percentage);
                }

                //prevent sending form with errors
                form.onsubmit = function(e){
                  if(insertedPercentageResell.value === ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the percentage resell") ?>");
                  } else if(!isValidPercentage(insertedPercentageResell.value)){
                    e.preventDefault();
                    alert("<?= translate("The inserted the percentage resell is not valid") ?>");
                  } else if(Number(insertedPercentageResell.value) < 0.0 || Number(insertedPercentageResell.value) > 100.0){
                    e.preventDefault();
                    alert("<?= translate("The percentage is not between z and h") ?>");
                  }
                }
              </script>
            <?php
          } else {
            addParagraph(translate("You cant change the percentage resell because other artisans are now selling this your product"));
          }
          //End main content of this page
        } else {
          addParagraph(translate("You cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Edit percentage resell"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
