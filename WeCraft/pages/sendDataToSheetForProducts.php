<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Send a modfication of the sheet here
  //You need to be an artisan or a designer
  //You can use this page only if you are collaborating for the design of this product
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative design"),"cookieBack");
      //Receive post request from the sheet to update it
      $insertedSheet = $_POST['insertedSheet'];
      $insertedProductId = $_POST['insertedProductId'];
      $insertedOldSheet = $_POST['insertedOldSheet'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the design of this product"));
      } else {
        //Real content of the page
        //Check if the sheet is sendable
        $sheetContent = obtainSheetContent($insertedProductId);
        if($sheetContent["content"] == $insertedOldSheet || $sheetContent["content"] == $insertedSheet){
          //Send the sheet and redirect
          updateSheet($insertedSheet,$_SESSION["userId"],$insertedProductId);
          addRedirect("./cooperativeDesignProduct.php?id=".urlencode($insertedProductId));
        } else {
          //Show merge page
          addTitle(translate("Merge the sheet"));
          addParagraph(translate("Previous update")." ".$sheetContent["lastUpdateWhen"]." ".translate("froms")." ".$sheetContent["name"]." ".$sheetContent["surname"]." (".$sheetContent["email"].")");
          addParagraph(translate("In the meantime someone has edited the sheet and so you have to merge the contents"));
          addParagraph(translate("Your content").":");
          addParagraphNewlineCapabilities($insertedSheet);
          startForm1();
          startForm2("./sendDataToSheetForProducts.php");
          addLongTextField(translate("Edit the incoming sheet"),"insertedSheet",100000);
          addHiddenField("insertedProductId",$insertedProductId);
          addHiddenField("insertedOldSheet",$sheetContent["content"]);
          endForm(translate("Save changes"));
          ?>
            <script>
              //form inserted parameters
              const form = document.querySelector('form');
              const insertedSheet = document.getElementById('insertedSheet');

              //Load form fields starting values
              insertedSheet.value = "<?= newlineForJs($sheetContent["content"]) ?>";
            </script>
          <?php
          addButtonLinkJsVersion(translate("Discard changes"),"./cooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
        }
      }  
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This page is available only with post request"));
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
