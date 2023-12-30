<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add an image to this product (if you are the owner of this product) by the id of the product (then you can optionally repeat to add other images)
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for adding images to this product
    $insertedProductId = $_POST['insertedProductId'];
    upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($insertedProductId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this product exists and check that the user is the artisan owner of this product
      if(doesThisProductExists($insertedProductId)){
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Now checks on the file before adding an image to this product
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
              addImageToAProduct($insertedProductId,$fileExtension,$imgData);
              addParagraph(translate("Done"));
              addButtonLink(translate("Add another image"),"addImagesToThisProduct.php?id=".urlencode($insertedProductId));
            }
          } else {
            addParagraph(translate("You have missed to select the new image"));
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
      upperPartOfThePage(translate("Edit product"),"./product.php?id=".urlencode($_GET["id"]));
      if(doesThisProductExists($_GET["id"])){
        //Verify to be the owner of this product
        $productInfos = obtainProductInfos($_GET["id"]);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Content of this page
          addParagraph(translate("Product").": ".$productInfos["name"]);
          //Title Add images to this product
          addTitle(translate("Add images to this product"));
          //Form to insert data to add an image to this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addFileField(translate("Image"),"insertedImage");
          addHiddenField("insertedProductId",$_GET["id"]);
          endForm(translate("Submit"));
          ?>
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
