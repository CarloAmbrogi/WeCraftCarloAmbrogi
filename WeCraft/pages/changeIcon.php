<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change icon
  doInitialScripts();
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./more.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change the icon
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Change your icon
        if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
          //You have chosen to send the file icon
          $fileName = $_FILES["insertedIcon"]["name"];
          $fileType = $_FILES["insertedIcon"]["type"];
          $fileSize = $_FILES['insertedIcon']['size'];
          $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
          if($fileSize > maxSizeForAFile){
            addParagraph(translate("The file is too big"));
          } else if(!array_key_exists($fileExtension, permittedExtensions)){
            addParagraph(translate("The extension of the file is not an image"));
          } else if(!in_array($fileType, permittedExtensions)){
            addParagraph(translate("The file is not an image"));
          } else {
            //update the icon with the new file icon
            $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
            changeIconOfAnUser($_SESSION["userId"],$fileExtension,$imgData);
            addParagraph(translate("Done"));
          }
        } else {
          addParagraph(translate("You have missed to select the new icon"));
        }
      }
    } else {
      //Content of the page change icon
      ?>
        <!-- Title Change icon -->
        <?php addTitle(translate("Change icon")); ?>
        <!-- Form to insert data to change the icon -->
        <div class="row mb-3">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="formFile" class="form-label"><?= translate("Icon") ?></label>
              <input class="form-control" type="file" id="formFile" name="insertedIcon">
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
              alert("<?= translate("You have missed to select the new icon") ?>");
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
