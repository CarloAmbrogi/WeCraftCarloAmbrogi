<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Remove images
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan" && $kindOfTheAccountInUse != "Designer"){
    //This page is visible only for artisans and designers
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Remove images"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to add images to your profile
      $numberOfImages = getNumberImagesOfThisUser($_SESSION["userId"]);
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if(!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if ($numberOfImages == 0){
        addParagraph(translate("You have no images"));
      } else {
        //Remove images
        $images = getImagesOfThisUser($_SESSION["userId"]);
        $removedAtLeastAnImage = false;
        for($i=0;$i<$numberOfImages;$i++){
          $idOfThisImage = $images[$i]["id"];
          $postOfThisImage = $_POST['image'.$idOfThisImage];
          if($postOfThisImage == true){
            removeThisImageToAnUser($_SESSION["userId"],$idOfThisImage);
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
      //Content of the page remove images
      $numberOfImages = getNumberImagesOfThisUser($_SESSION["userId"]);
      if($numberOfImages == 0){
        addParagraph(translate("You have no images"));
      } else {
        ?>
          <!-- Title Remove images -->
          <?php addTitle(translate("Remove images")); ?>
          <!-- Form to remove images -->
          <div class="row mb-3">
            <p><?= translate("Select the images to remove") ?></p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
              <?php
                $images = getImagesOfThisUser($_SESSION["userId"]);
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
              ?>
              <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
              <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
            </form>
          </div>
        <?php
      }
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
