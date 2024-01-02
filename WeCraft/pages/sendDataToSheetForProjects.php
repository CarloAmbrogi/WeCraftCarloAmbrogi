<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Send a modfication of the sheet here
  //You need to be an artisan or a designer
  //You can use this page only if you are collaborating for the design of this project
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative design"),"cookieBack");
      //Receive post request from the sheet to update it
      $insertedSheet = $_POST['insertedSheet'];
      $insertedProjectId = $_POST['insertedProjectId'];
      $insertedOldSheet = $_POST['insertedOldSheet'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProjectExists($insertedProjectId)){
        addParagraph(translate("This project doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$insertedProjectId)){
        addParagraph(translate("You are not a collaborator for the design of this project"));
      } else {
        //Real content of the page
        //Check if the sheet is sendable
        $sheetContent = obtainSheetContentProjects($insertedProjectId);
        if($sheetContent["content"] == $insertedOldSheet || $sheetContent["content"] == $insertedSheet){
          //Send the sheet and redirect
          updateSheetProject($insertedSheet,$_SESSION["userId"],$insertedProjectId);
          addRedirect("./cooperativeDesignProject.php?id=".urlencode($insertedProjectId));
        } else {
          //Show merge page
          addTitle(translate("Merge the sheet"));
          addParagraph(translate("Previous update")." ".$sheetContent["lastUpdateWhen"]." ".translate("froms")." ".$sheetContent["name"]." ".$sheetContent["surname"]." (".$sheetContent["email"].")");
          addParagraph(translate("In the meantime someone has edited the sheet and so you have to merge the contents"));
          addParagraph(translate("Your content").":");
          addParagraphNewlineCapabilities($insertedSheet);
          startForm1();
          startForm2("./sendDataToSheetForProjects.php");
          addLongTextField(translate("Edit the incoming sheet"),"insertedSheet",100000);
          addHiddenField("insertedProjectId",$insertedProjectId);
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
          addButtonLinkJsVersion(translate("Discard changes"),"./cooperativeDesignProject.php?id=".urlencode($_GET["id"]));
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
