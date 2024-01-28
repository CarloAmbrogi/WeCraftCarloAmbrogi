<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page to write a review of a product
  //You can write a review only if you have purchased it
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Guest"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request for adding a review to this product
      $insertedProductId = $_POST['insertedProductId'];
      upperPartOfThePage(translate("Write a review"),"./product.php?id=".urlencode($insertedProductId));
      $insertedStars = trim($_POST['insertedStars']);
      $insertedReview = trim($_POST['insertedReview']);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedReview == ""){
        addParagraph(translate("You have missed to insert the review"));
      } else if(strlen($insertedReview) > 2046){
        addParagraph(translate("The review is too long"));
      } else {
        $numInsertedStars = 1;
        if($insertedStars == "s2"){
          $numInsertedStars = 2;
        }
        if($insertedStars == "s3"){
          $numInsertedStars = 3;
        }
        if($insertedStars == "s4"){
          $numInsertedStars = 4;
        }
        if($insertedStars == "s5"){
          $numInsertedStars = 5;
        }
        if(doesThisProductExists($insertedProductId)){
          if(hasThisUserPurchasedThisItem($_SESSION["userId"],$insertedProductId)){
            //Load the review
            uploadReview($_SESSION["userId"],$insertedProductId,$numInsertedStars,$insertedReview);
            addParagraph(translate("Done"));
          } else {
            addParagraph("You cant write a review of this product because you havent purchased it");
          }
        } else {
          addParagraph(translate("This product doesnt exists"));
        }
      }
    } else {
      if(isset($_GET["id"])){
        upperPartOfThePage(translate("Write a review"),"./product.php?id=".urlencode($_GET["id"]));
        if(doesThisProductExists($_GET["id"])){
          if(hasThisUserPurchasedThisItem($_SESSION["userId"],$_GET["id"])){
            //Content of this page
            $productInfos = obtainProductInfos($_GET["id"]);
            addParagraph(translate("Product").": ".$productInfos["name"]);
            //Title Write a review
            addTitle(translate("Write a review"));
            //Form to insert data edit product general info of this product
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            ?>
              <label for="formStars" class="form-label"><?= translate("Stars") ?></label>
              <select id="insertedStars" name="insertedStars">
                <option value="s1">⭐️</option>
                <option value="s2">⭐️⭐️</option>
                <option value="s3">⭐️⭐️⭐️</option>
                <option value="s4">⭐️⭐️⭐️⭐️</option>
                <option value="s5">⭐️⭐️⭐️⭐️⭐️</option>
              </select>
            <?php
            addLongTextField(translate("Review"),"insertedReview",2046);
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const insertedReview = document.getElementById('insertedReview');

                //prevent sending form with errors
                form.onsubmit = function(e){
                  if(insertedReview.value.trim() == ""){
                    e.preventDefault();
                    alert("<?= translate("You have missed to insert the review") ?>");
                  }
                }
              </script>
            <?php
            //End main content of this page
          } else {
            addParagraph("You cant write a review of this product because you havent purchased it");
          }
        } else {
          addParagraph(translate("This product doesnt exists"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        //You have missed to specify the get param id of the product
        addParagraph(translate("You have missed to specify the get param id of the product"));
      }
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("For this functionality you have to do the log in"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
