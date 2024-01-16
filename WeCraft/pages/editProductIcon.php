<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit the icon of a product (if you are the owner of this product) by the id of the product
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for editing the icon of this product
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
          //Checks on the user are ok; now checks on the file
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
              changeIconOfAProduct($insertedProductId,$fileExtension,$imgData);
              addParagraph(translate("Done"));
              //sync also on Magis
              $imageUrl = blobToFile($fileExtension,$imgData);
              $idOfThisProduct = $insertedProductId;
              doGetRequest(MagisBaseUrl."apiForWeCraft/changeImageUrlMetadata.php?password=".urlencode(PasswordCommunicationWithMagis)."&imageUrl=".urlencode($imageUrl)."&url=".urlencode(WeCraftBaseUrl."pages/product.php?id=".$idOfThisProduct));  
            }
          } else {
            addParagraph(translate("You have missed to select the new icon"));
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
          //Title Edit icon of this product
          addTitle(translate("Edit icon of this product"));
          //Form to insert data edit product general info of this product
          startForm1();
          startForm2($_SERVER['PHP_SELF']);
          addFileField(translate("Icon"),"insertedIcon");
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
                  alert("<?= translate("You have missed to select the new icon") ?>");
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
