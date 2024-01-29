<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for the collaboration for a project (get param id is te id of the project related to this collaboration)
  //You need to be an artisan or a designer
  //You can see this page only if you are collaborating for the design of this project
  //If you are the owner of the project you can add partecipants and delete this collaboration
  //In this page there is a collaboration sheet
  //You can do actions only if the project is not confirmed
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProjectExists($_GET["id"])){
      if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer" || $_SESSION["userId"] == "admin"){        
        //Check you are a collaborator
        if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["id"]) || $_SESSION["userId"] == "admin"){
          addScriptAddThisPageToCronology();
          upperPartOfThePage(translate("Cooperative design"),"cookieBack");
          //Real content of this page
          $projectInfos = obtainProjectInfos($_GET["id"]);
          //Show in the left col the related project and in the right col some options related to the collaboration
          startRow();
          startCol();
          //Show the related project
          addParagraph(translate("Project").":");
          $fileImageToVisualize = genericProjectImage;
          if(isset($projectInfos['icon']) && ($projectInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($projectInfos["iconExtension"],$projectInfos['icon']);
          }
          $text1 = translate("Price").": ".floatToPrice($projectInfos["price"]);
          $text2 = translate("Percentage to designer").": ".$projectInfos["percentageToDesigner"]."%";
          addACardForTheGrid("./project.php?id=".urlencode($projectInfos["id"]),$fileImageToVisualize,htmlentities($projectInfos["name"]),$text1,$text2);
          endCol();
          startCol();
          //Show some options related to the collaboration (in case the project is not completed)
          $thisProjectIsCompleted = false;
          if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
            $thisProjectIsCompleted = true;
          }
          if(!$thisProjectIsCompleted){
            if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"]){
              //Options in case you are the owner
              addButtonLink(translate("Add partecipants"),"./addPartecipantsCooperativeDesignProject.php?id=".urlencode($_GET["id"]));
              addButtonLink(translate("Remove partecipants"),"./removePartecipantsCooperativeDesignProject.php?id=".urlencode($_GET["id"]));
              addButtonLink(translate("Coordinate collaboration"),"./");
              addButtonLink(translate("Delete this collaboration"),"./deleteCooperativeDesignProject.php?id=".urlencode($_GET["id"]));
            } else {
              //Options in case you aren't the owner
              addButtonLink(translate("Leave the group"),"./leaveGroupCooperativeDesignProject.php?id=".urlencode($_GET["id"]));
            }
          }
          //Options for every collaborator
          addButtonLink(translate("Send message"),"./chat.php?chatKind=".urlencode("project")."&chatWith=".urlencode($_GET["id"]));
          addButtonLink(translate("See partecipants"),"./seePartecipantsCooperativeDesignProject.php?id=".urlencode($_GET["id"]));
          endCol();
          endRow();
          //Show the sheet
          addTitle(translate("Sheet"));
          $sheetContent = obtainSheetContentProjects($_GET["id"]);
          if(isset($sheetContent["lastUpdateFrom"]) && $sheetContent["lastUpdateFrom"] != null){
            addParagraph(translate("Last update")." ".$sheetContent["lastUpdateWhen"]." ".translate("froms")." ".$sheetContent["name"]." ".$sheetContent["surname"]." (".$sheetContent["email"].")");
          } else {
            addParagraph(translate("Sheet created in")." ".$sheetContent["lastUpdateWhen"]);
          }
          if(!$thisProjectIsCompleted){
            startForm1();
            startForm2("./sendDataToSheetForProjects.php");
            addLongTextField("","insertedSheet",100000);
            addHiddenField("insertedProjectId",$_GET["id"]);
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
          } else {
            addParagraphNewlineCapabilities($sheetContent["content"]);
            addParagraph(translate("Is not possible to modify the sheet because the project is completed"));
          }
          forceThisPageReloadWhenBrowserBackButton();
        } else {
          upperPartOfThePage(translate("Error"),"");
          addParagraph(translate("You are not a collaborator for the design of this project"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        addParagraph(translate("This page is visible only to artisans and designers"));
      }
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This project doesnt exists"));
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    //You have missed to specify the get param id of the project
    addParagraph(translate("You have missed to specify the get param id of the project"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
