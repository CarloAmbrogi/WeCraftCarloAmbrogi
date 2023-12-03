<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit product category of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the product category of this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".$insertedProductId);
    $insertedCategory = $_POST['insertedCategory'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    $possibleCategories = categories;
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedCategory != "Nonee" && !in_array($insertedCategory,$possibleCategories)){
      addParagraph(translate("Category not valid"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Edit the category of this product
          updateCategoryOfAProduct($insertedProductId,$insertedCategory);
          addParagraph(translate("Done"));
        } else {
          addParagraph(translate("you cant modify this product"));
        }
      } else {
        addParagraph(translate("This product doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Edit product"),"./product.php?id=".$_GET["id"]);
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          //Title Edit category of this product
          addTitle(translate("Edit category of this product"));
          //Form to insert data edit the category of this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          ?>
            <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
            <select id="insertedCategory" name="insertedCategory">
              <option value="Nonee"><?= translate("Nonee") ?></option>
                <?php
                  $possibleCategories = categories;
                  foreach($possibleCategories as &$category){
                ?>
                  <option value="<?= $category ?>"><?= translate($category) ?></option>
                <?php
                  }
                ?>
            </select>
          <?php
          addHiddenField("insertedProductId",$_GET["id"]);
          endForm(translate("Submit"));
          //End main content of this page
        } else {
          addParagraph(translate("you cant modify this product"));
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
