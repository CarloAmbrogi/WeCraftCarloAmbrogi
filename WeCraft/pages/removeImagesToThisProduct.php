<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Remove images to this product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for removing images from this product
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
          //Now check if this product has some images (else it's not possible to remove images)
          $numberOfImages = getNumberImagesOfThisProduct($insertedProductId);
          if ($numberOfImages == 0){
            addParagraph(translate("You have no images"));
          } else {
            //Remove images from this product
            $images = getImagesOfThisProduct($insertedProductId);
            $removedAtLeastAnImage = false;
            for($i=0;$i<$numberOfImages;$i++){
              $idOfThisImage = $images[$i]["id"];
              $postOfThisImage = $_POST['image'.$idOfThisImage];
              if($postOfThisImage == true){
                removeThisImageToAProduct($insertedProductId,$idOfThisImage);
                $removedAtLeastAnImage = true;
              }
            }
            if($removedAtLeastAnImage){
              addParagraph(translate("Done"));
            } else {
              addParagraph(translate("No image has been removed"));
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
          //Title Remove images to this product
          addTitle(translate("Remove images to this product"));
          $numberOfImages = getNumberImagesOfThisProduct($_GET["id"]);
          if($numberOfImages == 0){
            addParagraph(translate("You have no images"));
          } else {
            //Form to remove images
            startForm1();
            addParagraphInAForm(translate("Select the images to remove"));
            startForm2($_SERVER['PHP_SELF']);
            $images = getImagesOfThisProduct($_GET["id"]);
            for($i=0;$i<$numberOfImages;$i++){
              ?>
                <ul class="list-group">
                  <li class="list-group-item">
                    <div for="image<?= $images[$i]["id"] ?>" class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="image<?= $images[$i]["id"] ?>" name="image<?= $images[$i]["id"] ?>">
                      <?php addImage(blobToFile($images[$i]["imgExtension"],$images[$i]['image']),"Image ".($i+1)); ?>
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
