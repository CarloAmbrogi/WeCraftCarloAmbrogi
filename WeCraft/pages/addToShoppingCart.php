<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add a product by id (the id is sent as a get) to the shopping cart
  //This page is visible only to customers and other users
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for adding a certain quantity of this product to the shopping cart
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Product"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is a customer or in every case not a guest
      if(doesThisProductExists($insertedProductId)){
        if($kindOfTheAccountInUse != "Guest"){
          //Add a certain quantity of this product to the shopping cart
          $visualizeViewShoppingCartButton = false;
          $quantityOfThisProductInShoppingCartByThisUserRaw = getQuantityOfThisProductInShoppingCartByThisUser($insertedProductId,$_SESSION["userId"]);
          //Check that you cant buy from yourself
          $quantityOfThisProductInShoppingCartByThisUser = [];
          foreach ($quantityOfThisProductInShoppingCartByThisUserRaw as &$value){
            if($_SESSION["userId"] != $value["artisanId"]){
              array_push($quantityOfThisProductInShoppingCartByThisUser, $value);
            }
          }
          foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
            $insertedThisQuantity = 0;
            if(isset($_POST['insertedQuantity'.$value["artisanId"]])){
              $insertedThisQuantity = $_POST['insertedQuantity'.$value["artisanId"]];
            }
            if($insertedThisQuantity == ""){
              $insertedThisQuantity = 0;
            }
            //Check on this inserted quantity
            if(strlen($insertedThisQuantity) > 5){
              addParagraph(translate("Error on quantity from")." ".$value["shopName"].":");
              addParagraph(translate("The quantity is too big"));
            } else if(!isValidQuantity($insertedThisQuantity)){
              addParagraph(translate("Error on quantity from")." ".$value["shopName"].":");
              addParagraph(translate("The quantity is not a number"));
            } else {
              if($insertedThisQuantity > 0){
                $visualizeViewShoppingCartButton = true;
              }
              //Update the quantity in the shopping cart
              updateQuantityOfThisProductInShoppingCartForThisUser($insertedProductId,$insertedThisQuantity,$_SESSION["userId"],$value["artisanId"]);
              addParagraph(translate("Now in the shopping cart")." ".$insertedThisQuantity." ".translate("froms")." ".$value["shopName"]);
            }
          }
          addButtonLink(translate("Continue with your purchases"),"./product.php?id=".urlencode($insertedProductId));
          if($visualizeViewShoppingCartButton){
            addButtonLink(translate("Go to shopping cart"),"./shoppingCart.php");
          }
        } else {
          addParagraph(translate("You have to be logged to see this page"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Product"),"./product.php?id=".urlencode($_GET["id"]));
      if($kindOfTheAccountInUse != "Guest"){
        if(doesThisProductExists($_GET["id"])){
          //Real content of this page
          $productInfos = obtainProductInfos($_GET["id"]);
          //Check that you can't buy a your product
          if($_SESSION["userId"] != $productInfos["artisan"]){
            addParagraph(translate("Product").": ".$productInfos["name"]." (".translate("available")." ".$productInfos["quantity"].")");
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            $quantityOfThisProductInShoppingCartByThisUserRaw = getQuantityOfThisProductInShoppingCartByThisUser($_GET["id"],$_SESSION["userId"]);
            //Check that you cant buy from yourself
            $quantityOfThisProductInShoppingCartByThisUser = [];
            foreach ($quantityOfThisProductInShoppingCartByThisUserRaw as &$value){
              if($_SESSION["userId"] != $value["artisanId"]){
                array_push($quantityOfThisProductInShoppingCartByThisUser, $value);
              }
            }
            $isTheFirst = true;
            foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
              if($isTheFirst){
                addShortTextField(translate("Quantity from the owner")." ".$value["shopName"].": (".translate("max available").": ".$value["maxQuantityAvailable"].")","insertedQuantity".$value["artisanId"],5);
              } else {
                addShortTextField(translate("Quantity from")." ".$value["shopName"].": (".translate("max available").": ".$value["maxQuantityAvailable"].")","insertedQuantity".$value["artisanId"],5);
              }
              $isTheFirst = false;
              startRow();
              startCol();
              addApiActionViaJsLink("-","","dec".$value["artisanId"],"decQuantityValue".$value["artisanId"]);
              endCol();
              addColMiniSpacer();
              startCol();
              addApiActionViaJsLink("+","","inc".$value["artisanId"],"incQuantityValue".$value["artisanId"]);
              endCol();
              endRow();
            }
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                <?php
                  foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
                    ?>
                      const insertedQuantity<?= $value["artisanId"] ?> = document.getElementById('insertedQuantity<?= $value["artisanId"] ?>');
                    <?php
                  }
                ?>
    
                //Max available quantity
                <?php
                  foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
                    ?>
                      const maxAvailableQuantity<?= $value["artisanId"] ?> = <?= $value["maxQuantityAvailable"] ?>;
                    <?php
                  }
                ?>
    
                //Load form fields starting values
                <?php
                  foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
                    ?>
                      insertedQuantity<?= $value["artisanId"] ?>.value = "<?= $value["quantityInShoppingCart"] ?>";
                    <?php
                  }
                ?>
    
                function isValidQuantity(quantity){
                  const quantityRegex = /^[0-9]+$/;
                  return quantityRegex.test(quantity);
                }
    
                //Functions fot + and - buttons
                <?php
                  foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
                    ?>
                      function incQuantityValue<?= $value["artisanId"] ?>(){
                        if(!isValidQuantity(insertedQuantity<?= $value["artisanId"] ?>.value)){
                          insertedQuantity<?= $value["artisanId"] ?>.value = "0";
                        }
                        let q = Number(insertedQuantity<?= $value["artisanId"] ?>.value);
                        q = q + 1;
                        if(q > maxAvailableQuantity<?= $value["artisanId"] ?>){
                          q = maxAvailableQuantity<?= $value["artisanId"] ?>;
                        }
                        if(q < 0){
                          q = 0;
                        }
                        insertedQuantity<?= $value["artisanId"] ?>.value = q;
                      }
                      function decQuantityValue<?= $value["artisanId"] ?>(){
                        if(!isValidQuantity(insertedQuantity<?= $value["artisanId"] ?>.value)){
                          insertedQuantity<?= $value["artisanId"] ?>.value = "0";
                        }
                        let q = Number(insertedQuantity<?= $value["artisanId"] ?>.value);
                        q = q - 1;
                        if(q > maxAvailableQuantity<?= $value["artisanId"] ?>){
                          q = maxAvailableQuantity<?= $value["artisanId"] ?>;
                        }
                        if(q < 0){
                          q = 0;
                        }
                        insertedQuantity<?= $value["artisanId"] ?>.value = q;
                      }
                    <?php
                  }
                ?>
                
                //prevent sending form with errors
                form.onsubmit = function(e){
                  <?php
                    foreach ($quantityOfThisProductInShoppingCartByThisUser as &$value){
                      ?>
                        if(insertedQuantity<?= $value["artisanId"] ?>.value.trim() == ""){
                          e.preventDefault();
                          alert("<?= translate("You have missed to insert the quantity") ?>");
                          return;
                        }
                        if(!isValidQuantity(insertedQuantity<?= $value["artisanId"] ?>.value)){
                          e.preventDefault();
                          alert("<?= translate("The quantity is not a number") ?>");
                          return;
                        }
                        if(Number(insertedQuantity<?= $value["artisanId"] ?>.value) > maxAvailableQuantity<?= $value["artisanId"] ?>){
                          e.preventDefault();
                          alert("<?= translate("This quantity is not available") ?>");
                          return;
                        }
                      <?php
                    }
                  ?>
                }
              </script>
            <?php
          } else {
            addParagraph(translate("You cant buy a your product"));
          }
        } else {
          addParagraph(translate("This product doesnt exists"));
        }
      } else {
        addParagraph(translate("You have to be logged to see this page"));
      }
    } else {
      //You have missed to specify the get param id of the product
      upperPartOfThePage(translate("Product"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
