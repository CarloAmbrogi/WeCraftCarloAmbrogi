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
      //AAAAAAAAAA

      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Add a new product
        //AAAAAAAAAAA
        
      }
    } else {
      //Content of the page add a new product
      ?>
        <!-- Title Add a new product -->
        <?php addTitle(translate("Add a new product")); ?>
        <!-- Form to insert data to add a new product -->
        <div class="row mb-3">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            
          
            <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
            <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
          </form>
        </div>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          
        
          function getFileExtension(filename){
            return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
          }

          function isValidPrice(price){
            //The price shoud have at least an integer digit and exactly 2 digits after the floating point
            const priceRegex = /^[0-9]+\.[0-9][0-9]$/;
            return priceRegex.test(price);
          }

          //prevent sending form with errors
          form.onsubmit = function(e){


            e.preventDefault();
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
