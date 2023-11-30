<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add images
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan" && $kindOfTheAccountInUse != "Designer"){
    //This page is visible only for artisans and designers
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Add images"),"./more.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to add images to your profile
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Add a new image
        if(isset($_FILES['insertedImage']) && $_FILES['insertedImage']['error'] == 0){
          //You have chosen to send a new image to add
          $fileName = $_FILES["insertedImage"]["name"];
          $fileType = $_FILES["insertedImage"]["type"];
          $fileSize = $_FILES['insertedImage']['size'];
          $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
          if($fileSize > maxSizeForAFile){
            addParagraph(translate("The file is too big"));
          } else if(!array_key_exists($fileExtension, permittedExtensions)){
            addParagraph(translate("The extension of the file is not an image"));
          } else if(!in_array($fileType, permittedExtensions)){
            addParagraph(translate("The file is not an image"));
          } else {
            //add the new image with the new file image
            $imgData = file_get_contents($_FILES['insertedImage']['tmp_name']);
            addImageToAnUser($_SESSION["userId"],$fileExtension,$imgData);
            addParagraph(translate("Done"));
            addButtonLink(translate("Add another image"),"./addImages.php");
          }
        } else {
          addParagraph(translate("You have missed to select the new image"));
        }
      }
    } else {
      //Content of the page add images
      ?>
        <!-- Title Add images -->
        <?php addTitle(translate("Add images")); ?>
        <!-- Form to insert data to add a new image -->
        <div class="row mb-3">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="formFile" class="form-label"><?= translate("Icon") ?></label>
              <input class="form-control" type="file" id="formFile" name="insertedImage">
            </div>
            <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
            <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
          </form>
        </div>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const inputFile = document.getElementById('formFile');
        
          function getFileExtension(filename){
            return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
          }

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(inputFile.files[0]){
              let file = inputFile.files[0];
              let fileSize = file.size;
              let fileName = file.name;
              let fileExtension = getFileExtension(fileName);
              const permittedExtensions = ["jpg","jpeg","gif","png","webp","heic"];
              if(fileSize > <?= maxSizeForAFile ?>){
                e.preventDefault();
                alert("<?= translate("The file is too big") ?>");
              } else if(!permittedExtensions.includes(fileExtension)){
                e.preventDefault();
                alert("<?= translate("The extension of the file is not an image") ?>");
              }
            } else {
              alert("<?= translate("You have missed to select the new image") ?>");
              e.preventDefault();
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>