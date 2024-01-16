<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit general info of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the general info of this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($insertedProductId));
    $insertedName = trim($_POST['insertedName']);
    $insertedDescription = trim($_POST['insertedDescription']);
    $insertedPrice = $_POST['insertedPrice'];
    $insertedQuantity = $_POST['insertedQuantity'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedName == ""){
      addParagraph(translate("You have missed to insert the name"));
    } else if(strlen($insertedName) > 24){
      addParagraph(translate("The name is too long"));
    } else if($insertedDescription == ""){
      addParagraph(translate("You have missed to insert the description"));
    } else if(strlen($insertedDescription) > 2046){
      addParagraph(translate("The description is too long"));
    }  else if($insertedPrice == ""){
      addParagraph(translate("You have missed to insert the price"));
    } else if(strlen($insertedPrice) > 24){
      addParagraph(translate("The price is too long"));
    } else if(!isValidPrice($insertedPrice)){
      addParagraph(translate("The price is not in the format number plus dot plus two digits"));
    } else if($insertedQuantity == ""){
      addParagraph(translate("You have missed to insert the quantity"));
    } else if(strlen($insertedQuantity) > 5){
      addParagraph(translate("The quantity is too big"));
    } else if(!isValidQuantity($insertedQuantity)){
      addParagraph(translate("The quantity is not a number"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Edit general info of this product
          updateGeneralInfoOfAProduct($insertedProductId,$insertedName,$insertedDescription,$insertedPrice,$insertedQuantity);
          addParagraph(translate("Done"));
          //sync also on Magis
          $titleMetadata = $insertedName;
          $idOfThisProduct = $insertedProductId;
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeTitleMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&title=".urlencode($titleMetadata)."&url=".urlencode(WeCraftBaseUrl."pages/product.php?id=".$idOfThisProduct),1);
          doGetRequest(MagisBaseUrl."apiForWeCraft/changeDescriptionMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&description=".urlencode($insertedDescription)."&url=".urlencode(WeCraftBaseUrl."pages/product.php?id=".$idOfThisProduct),2);
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
          //Title Edit product general info of this product
          addTitle(translate("Edit product general info of this product"));
          //Form to insert data edit product general info of this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addShortTextField(translate("Name"),"insertedName",24);
          addLongTextField(translate("Description"),"insertedDescription",2046);
          addShortTextField(translate("Price"),"insertedPrice",24);
          addShortTextField(translate("Quantity"),"insertedQuantity",5);
          addHiddenField("insertedProductId",$_GET["id"]);
          endForm(translate("Submit"));
          ?>
            <script>
              //form inserted parameters
              const form = document.querySelector('form');
              const insertedName = document.getElementById('insertedName');
              const insertedDescription = document.getElementById('insertedDescription');
              const insertedPrice = document.getElementById('insertedPrice');
              const insertedQuantity = document.getElementById('insertedQuantity');

              //Load form fields starting values
              insertedName.value = "<?= $productInfos["name"] ?>";
              insertedDescription.value = "<?= newlineForJs($productInfos["description"]) ?>";
              insertedPrice.value = "<?= floatToPrice($productInfos["price"]) ?>";
              insertedQuantity.value = "<?= $productInfos["quantity"] ?>";
    
              function isValidQuantity(quantity){
                const quantityRegex = /^[0-9]+$/;
                return quantityRegex.test(quantity);
              }
    
              function isValidPrice(price){
                //The price shoud have at least an integer digit and exactly 2 digits after the floating point
                const priceRegex = /^[0-9]+\.[0-9][0-9]$/;
                return priceRegex.test(price);
              }
    
              //prevent sending form with errors
              form.onsubmit = function(e){
                if(insertedName.value.trim() == ""){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the name") ?>");
                } else if(insertedDescription.value.trim() == ""){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the description") ?>");
                } else if(insertedPrice.value.trim() == ""){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the price") ?>");
                } else if(insertedQuantity.value.trim() == ""){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the quantity") ?>");
                } else if(!isValidQuantity(insertedQuantity.value)){
                  e.preventDefault();
                  alert("<?= translate("The quantity is not a number") ?>");
                } else if(!isValidPrice(insertedPrice.value)){
                  e.preventDefault();
                  alert("<?= translate("The price is not in the format number plus dot plus two digits") ?>");
                }
              }
            </script>
          <?php
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
