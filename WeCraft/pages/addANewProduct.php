<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add a new product
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Add new product"),"./artisan.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to add a new product
      $insertedName = $_POST['insertedName'];
      $insertedDescription = $_POST['insertedDescription'];
      $insertedPrice = $_POST['insertedPrice'];
      $insertedQuantity = $_POST['insertedQuantity'];
      $insertedCategory = $_POST['insertedCategory'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      $possibleCategories = categories;
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedName == ""){
        addParagraph(translate("You have missed to insert the name"));
      } else if(strlen($insertedName) > 24){
        addParagraph(translate("The name is too long"));
      } else if($insertedDescription == ""){
        addParagraph(translate("You have missed to insert the description"));
      } else if(strlen($insertedDescription) > 2046){
        addParagraph(translate("The description is too long"));
      } else if($insertedPrice == ""){
        addParagraph(translate("You have missed to insert the price"));
      } else if(strlen($insertedPrice) > 24){
        addParagraph(translate("The price is too long"));
      } else if(!isValidPrice($insertedPrice)){
        addParagraph(translate("The price is not in the format number plus dot plus two digits"));
      } else if($insertedQuantity == ""){
        addParagraph(translate("You have missed to insert the quantity"));
      } else if(strlen($insertedQuantity) > 5){
        addParagraph(translate("The quantity is too big"));
      } else if(!isValidQuantity($insertedQuantity)){
        addParagraph(translate("The quantity is not a number"));
      } else if($insertedCategory != "Nonee" && !in_array($insertedCategory,$possibleCategories)){
        addParagraph(translate("Category not valid"));
      } else {
        //Copyright check
        $productsPreviewCopyrightCheck = copyrightCheck($_SESSION["userId"],$insertedName,$insertedCategory);
        startCardGrid();
        $foundAProduct = false;
        foreach($productsPreviewCopyrightCheck as &$singleProductPreview){
          $foundAProduct = true;
          $fileImageToVisualize = genericProductImage;
          if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
          }
          $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
          $text2 = translate("Quantity available").": ".$singleProductPreview["quantity"];
          addACardForTheGrid("./product.php?id=".$singleProductPreview["id"],$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
        }
        endCardGrid();
        if($foundAProduct == true){
          addParagraph(translate("Is not possible to proceed because the copyright check has found theese similar products"));
          addButtonLink(translate("Return to your artisan page"),"./artisan.php");
        } else {
          //Add a new product
          if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
            //You have chosen to send the file icon
            $fileName = $_FILES["insertedIcon"]["name"];
            $fileType = $_FILES["insertedIcon"]["type"];
            $fileSize = $_FILES['insertedIcon']['size'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $insertCorrectlyTheIcon = false;
            if($fileSize > maxSizeForAFile){
              addParagraph(translate("The file is too big"));
            } else if(!array_key_exists($fileExtension, permittedExtensions)){
              addParagraph(translate("The extension of the file is not an image"));
            } else if(!in_array($fileType, permittedExtensions)){
              addParagraph(translate("The file is not an image"));
            } else {
              $insertCorrectlyTheIcon = true;
              //add the new product with file icon
              $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
              addANewProductWithIcon($_SESSION["userId"],$insertedName,$insertedDescription,$fileExtension,$imgData,$insertedPrice,$insertedQuantity,$insertedCategory);
              addParagraph(translate("Your data has been loaded correctly"));
            }
            if($insertCorrectlyTheIcon == false){
              //add the new product without file icon (because of error in the icon)
              addANewProductWithoutIcon($_SESSION["userId"],$insertedName,$insertedDescription,$insertedPrice,$insertedQuantity,$insertedCategory);
              addParagraph(translate("Your data has been loaded correctly except for the icon but you will be able to change the icon later"));
            }
          } else {
            //add the new product without file icon
            addParagraph(translate("Your data has been loaded correctly"));
            addANewProductWithoutIcon($_SESSION["userId"],$insertedName,$insertedDescription,$insertedPrice,$insertedQuantity,$insertedCategory);
          }
          //Show button to return to your artisan page
          addParagraph(translate("Now you can optionaly edit the product, for example to add tags and images and you can start to sell this product"));
          addButtonLink(translate("Return to your artisan page"),"./artisan.php");
        }
      }
    } else {
      //Content of the page add a new product
      //Title Add a new product
      addTitle(translate("Add a new product"));
      //Form to insert data to add a new product
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      addShortTextField(translate("Name"),"insertedName",24);
      addLongTextField(translate("Description"),"insertedDescription",2046);
      addShortTextField(translate("Price"),"insertedPrice",24);
      addShortTextField(translate("Quantity"),"insertedQuantity",5);
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
        addFileField(translate("Icon optional"),"insertedIcon");
        endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedName = document.getElementById('insertedName');
          const insertedDescription = document.getElementById('insertedDescription');
          const insertedPrice = document.getElementById('insertedPrice');
          const insertedQuantity = document.getElementById('insertedQuantity');
          const insertedCategory = document.getElementById('insertedCategory');
          const inputFile = document.getElementById('formFile');
          
          function getFileExtension(filename){
            return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
          }

          function isValidQuantity(quantity){
            const quantityRegex = /^[0-9]+$/;
            return quantityRegex.test(quantity);
          }

          function isValidPrice(price){
            //The price shoud have at least an integer digit and exactly 2 digits after the floating point
            const priceRegex = /^[0-9]+\.[0-9][0-9]$/;
            return priceRegex.test(price);
          }

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedName.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the name") ?>");
            } else if(insertedDescription.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the description") ?>");
            } else if(insertedPrice.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the price") ?>");
            } else if(insertedQuantity.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the quantity") ?>");
            } else if(!isValidQuantity(insertedQuantity.value)){
              e.preventDefault();
              alert("<?= translate("The quantity is not a number") ?>");
            } else if(!isValidPrice(insertedPrice.value)){
              e.preventDefault();
              alert("<?= translate("The price is not in the format number plus dot plus two digits") ?>");
            } else if(inputFile.files[0]){
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
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
