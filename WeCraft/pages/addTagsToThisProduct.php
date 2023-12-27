<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add a tag to this product (if you are the owner of this product) by the id of the product (then you can optionally repeat to add other tags)
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for adding tags to this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($insertedProductId));
    $insertedTag = trim($_POST['insertedTag']);
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedTag == ""){
      addParagraph(translate("You have missed to insert the tag"));
    } else if(strlen($insertedTag) > 24){
      addParagraph(translate("The tag is too long"));
    } else if(!isValidTag($insertedTag)){
      addParagraph(translate("Tag not valid"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Add a tag to this product
          addATagToAProduct($insertedProductId,$insertedTag);
          addParagraph(translate("Done"));
          addButtonLink(translate("Add another tag"),"addTagsToThisProduct.php?id=".urlencode($insertedProductId));
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
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Title Add tags to this product
          addTitle(translate("Add tags to this product"));
          //Form to insert data to add a tag to this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addShortTextField(translate("Insert a tag to add"),"insertedTag",24);
          addHiddenField("insertedProductId",$_GET["id"]);
          endForm(translate("Submit"));
          ?>
            <script>
              //form inserted parameters
              const form = document.querySelector('form');
              const insertedTag = document.getElementById('insertedTag');

              function isValidTag(tag){
                const tagRegex = /^[a-zA-Z0-9]+$/;
                return tagRegex.test(tag);
              }

              //prevent sending form with errors
              form.onsubmit = function(e){
                if(insertedTag.value.trim() == ""){
                  e.preventDefault();
                  alert("<?= translate("You have missed to insert the tag") ?>");
                } else if(!isValidTag(insertedTag.value)){
                  e.preventDefault();
                  alert("<?= translate("Tag not valid") ?>");
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
