<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Stop the sell of this your product from certains other artisans
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for stopping to sell this your product from certains artisans
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Stop exchange sell"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is an artisan which is the owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Now check if this product is sold also by other artisans (else it's not possible to stop this product to be sold)
            $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($insertedProductId);
            if($numberOtherArtisansWhoAreSellingThisExchangeProduct == 0){
              addParagraph(translate("This product is not sold by other artisans"));
            } else {
              //Stop artisans selling this product
              $artisans = obtainPreviewOtherArtisansWhoAreSellingThisExchangeProduct($insertedProductId);
              $removedAtAnArtisan = false;
              for($i=0;$i<$numberOtherArtisansWhoAreSellingThisExchangeProduct;$i++){
                $idOfThisArtisan = $artisans[$i]["id"];
                $postOfThisArtisan = $_POST['artisan'.$idOfThisArtisan];
                if($postOfThisArtisan == true){
                  stopSellingThisExchangeProduct($idOfThisArtisan,$insertedProductId);
                  $removedAtAnArtisan = true;
                }
              }
              if($removedAtAnArtisan){
                addParagraph(translate("Done"));
              } else {
                addParagraph(translate("You havent stopped any artisan to sell this your product"));
              }
            }
          } else {
            addParagraph(translate("You are not an artisan"));
          }
        } else {
          addParagraph(translate("This is not a your product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Stop exchange sell"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product and to be an artisan
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          if($kindOfTheAccountInUse == "Artisan"){
            //Content of this page
            //Title Stop the sell of this product from certains artisans
            addTitle(translate("Stop the sell of this product from certains artisans"));
            $numberOtherArtisansWhoAreSellingThisExchangeProduct = obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
            if($numberOtherArtisansWhoAreSellingThisExchangeProduct > 0){
              //Form to select artisans of this exchange product
              startForm1();
              addParagraphInAForm(translate("Select the artisans you want to stop selling this your product"));
              startForm2($_SERVER['PHP_SELF']);
              $artisans = obtainPreviewOtherArtisansWhoAreSellingThisExchangeProduct($_GET["id"]);
              for($i=0;$i<$numberOtherArtisansWhoAreSellingThisExchangeProduct;$i++){
                ?>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <div for="artisan<?= $artisans[$i]["id"] ?>" class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="artisan<?= $artisans[$i]["id"] ?>" name="artisan<?= $artisans[$i]["id"] ?>">
                        <?php addParagraph($artisans[$i]['shopName']." (".$artisans[$i]['name']." ".$artisans[$i]['surname'].")"); ?>
                      </div>
                    </li>
                  </ul>
                <?php
              }
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Submit"));
            } else {
              addParagraph(translate("There is no artisan"));
            }
            //End main content of this page
          } else {
            addParagraph(translate("You are not an artisan"));
          }
        } else {
          addParagraph(translate("This is not a your product"));
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
