<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperative design for projects
  doInitialScripts();
  addScriptAddThisPageToCronology();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan" && $kindOfTheAccountInUse != "Designer"){
    //This page is visible only to artisans and designers
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperative design"),"./cooperate.php");
    addTitle(translate("Cooperation with others to realize a personalized product"));
    $category = "v1";
    if(isset($_GET["insertedCategory"])){
      if($_GET["insertedCategory"] == "v2"){
        $category = "v2";
      }
    }
    if($category == "v1"){
      addParagraph(translate("Here will be shown the projects for witch you are now collaborating with other artisans and designers"));
      addButtonLink(translate("Visualize instead the precedent projects"),"./cooperativeDesignForProjects.php?insertedCategory=v2");
    }
    if($category == "v2"){
      addParagraph(translate("Here will be shown the completed projects for witch you have collaborated with other artisans and designers in the past"));
      addButtonLink(translate("Visualize instead the current projects"),"./cooperativeDesignForProjects.php?insertedCategory=v1");
    }
    //Show the projects for whitch you are collaborating in a cooperative design
    $projectsForWhitchYouAreCollaborating = obtainProductsPreviewCooperativeDesignPersonalizedProducts($_SESSION["userId"]);
    startCardGrid();
    foreach($projectsForWhitchYouAreCollaborating as &$singleProjectPreview){
      $isCompleted = false;
      if(isset($singleProjectPreview['timestampReady']) && ($singleProjectPreview['timestampReady'] != null)){
        $isCompleted = true;
      }
      if(($category == "v1" && $isCompleted == false) || ($category == "v2" && $isCompleted == true)){
        $fileImageToVisualize = genericProjectImage;
        if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
          $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
        }
        $text1 = translate("Number of collaborators").": ".$singleProjectPreview["numberOfCollaborators"];
        $text2 = "";
        addACardForTheGrid("./cooperativeDesignProject.php?id=".urlencode($singleProjectPreview["projectId"]),$fileImageToVisualize,htmlentities($singleProjectPreview["projectName"]),$text1,$text2);  
      }
    }
    endCardGrid();
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
