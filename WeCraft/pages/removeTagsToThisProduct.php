<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Remove tags to this product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for removing tags from this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".$insertedProductId);
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Now check if this product has some tags (else it's not possible to remove tags)
          $numberOfTags = getNumberTagsOfThisProduct($insertedProductId);
          if ($numberOfTags == 0){
            addParagraph(translate("There is no tag"));
          } else {
            //Remove tags from this product
            $tags = getTagsOfThisProduct($insertedProductId);
            $removedAtLeastATag = false;
            for($i=0;$i<$numberOfTags;$i++){
              $idOfThisTag = $tags[$i]["id"];
              $postOfThisTag = $_POST['tag'.$idOfThisTag];
              if($postOfThisTag == true){
                removeThisTagToAProduct($insertedProductId,$idOfThisTag);
                $removedAtLeastATag = true;
              }
            }
            if($removedAtLeastATag){
              addParagraph(translate("Done"));
            } else {
              addParagraph(translate("No tag has been removed"));
            }
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
      upperPartOfThePage(translate("Edit product"),"./product.php?id=".$_GET["id"]);
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Title Remove tags to this product
          addTitle(translate("Remove tags to this product"));
          $numberOfTags = getNumberTagsOfThisProduct($_GET["id"]);
          if($numberOfTags == 0){
            addParagraph(translate("There is no tag"));
          } else {
            //Form to remove tags
            startForm1();
            addParagraphInAForm(translate("Select the tags to remove"));
            startForm2($_SERVER['PHP_SELF']);
            $tags = getTagsOfThisProduct($_GET["id"]);
            for($i=0;$i<$numberOfTags;$i++){
              ?>
                <ul class="list-group">
                  <li class="list-group-item">
                    <div for="tag<?= $tags[$i]["id"] ?>" class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="tag<?= $tags[$i]["id"] ?>" name="tag<?= $tags[$i]["id"] ?>">
                      <?php addParagraph($tags[$i]['tag']); ?>
                    </div>
                  </li>
                </ul>
              <?php
            }
            addHiddenField("insertedProductId",$_GET["id"]);
            endForm(translate("Submit"));
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
      upperPartOfThePage(translate("Edit product"),"");
      addParagraph(translate("You have missed to specify the get param id of the product"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
