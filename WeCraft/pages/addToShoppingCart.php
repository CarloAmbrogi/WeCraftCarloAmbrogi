<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add a product by id (the id is sent as a get) to the shopping cart
  //This page is visible only to customers
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    upperPartOfThePage(translate("Product"),"./product.php?id=".$_GET["id"]);
    if($kindOfTheAccountInUse == "Customer"){
      if(doesThisProductExists($_GET["id"])){
        //Real content of this page
        $productInfos = obtainProductInfos($_GET["id"]);
        addParagraph(translate("Product").": ".$productInfos["name"]." (".translate("available")." ".$productInfos["quantity"].")");
        startForm1();
        startForm2($_SERVER['PHP_SELF']);
        addShortTextField(translate("Quantity"),"insertedQuantity",5);
        startRow();
        startCol();
        addApiActionViaJsLink("-","","dec","decQuantityValue");
        endCol();
        addColMiniSpacer();
        startCol();
        addApiActionViaJsLink("+","","inc","incQuantityValue");
        endCol();
        endRow();
        addHiddenField("insertedProductId",$_GET["id"]);
        endForm(translate("Submit"));
        $quantityOfThisProductInShoppingCartByThisUser = getQuantityOfThisProductInShoppingCartByThisUser($_GET["id"],$_SESSION["userId"]);
        ?>
          <script>
            //form inserted parameters
            const form = document.querySelector('form');
            const insertedQuantity = document.getElementById('insertedQuantity');

            //Max available quantity
            const maxAvailableQuantity = <?= $productInfos["quantity"] ?>;

            //Load form fields starting values
            insertedQuantity.value = "<?= $quantityOfThisProductInShoppingCartByThisUser ?>";

            function isValidQuantity(quantity){
              const quantityRegex = /^[0-9]+$/;
              return quantityRegex.test(quantity);
            }

            //Functions fot + and - buttons
            function incQuantityValue(){
              if(!isValidQuantity(insertedQuantity.value)){
                insertedQuantity.value = "0";
              }
              let q = Number(insertedQuantity.value);
              q = q + 1;
              if(q > maxAvailableQuantity){
                q = maxAvailableQuantity;
              }
              insertedQuantity.value = q;
            }
            function decQuantityValue(){
              if(!isValidQuantity(insertedQuantity.value)){
                insertedQuantity.value = "0";
              }
              let q = Number(insertedQuantity.value);
              q = q - 1;
              if(q < 0){
                q = 0;
              }
              insertedQuantity.value = q;
            }
            
            //prevent sending form with errors
            form.onsubmit = function(e){
              if(insertedQuantity.value === ""){
                e.preventDefault();
                alert("<?= translate("You have missed to insert the quantity") ?>");
              } else if(!isValidQuantity(insertedQuantity.value)){
                e.preventDefault();
                alert("<?= translate("The quantity is not a number") ?>");
              } else if(Number(insertedQuantity.value) > maxAvailableQuantity){
                e.preventDefault();
                alert("<?= translate("This quantity is not available") ?>");
              }
            }
          </script>
        <?php
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    } else {
      addParagraph(translate("This page is visible only to customers"));
    }
  } else {
    //You have missed to specify the get param id of the product
    upperPartOfThePage(translate("Product"),"");
    addParagraph(translate("You have missed to specify the get param id of the product"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
