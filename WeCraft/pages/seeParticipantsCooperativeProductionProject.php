<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page to see the participants of the cooperation for the production of this project
  //(get param id is te id of the project to this collaboration)
  //You need to be an artisan or a designer
  //You can see this page only if you are collaborating for the production of this project
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProjectExists($_GET["id"])){
      if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){        
        //Check you are a collaborator
        if(isThisUserCollaboratingForTheProductionOfThisProject($_SESSION["userId"],$_GET["id"])){
          addScriptAddThisPageToCronology();
          upperPartOfThePage(translate("Cooperative production"),"cookieBack");
          //Real content of this page
          $projectInfos = obtainProjectInfos($_GET["id"]);
          addParagraph(translate("Project").": ".$projectInfos["name"]);
          $artisanWhoHasClaimedThisProjectId = $projectInfos["claimedByThisArtisan"];
          addParagraph(translate("Artisan who has claimed this project").":");
          startCardGrid();
          $previewArtisanWhoHasClaimedThisProject = obtainPreviewThisArtisan($artisanWhoHasClaimedThisProjectId);
          $fileImageToVisualize = genericUserImage;
          if(isset($previewArtisanWhoHasClaimedThisProject['icon']) && ($previewArtisanWhoHasClaimedThisProject['icon'] != null)){
            $fileImageToVisualize = blobToFile($previewArtisanWhoHasClaimedThisProject["iconExtension"],$previewArtisanWhoHasClaimedThisProject['icon']);
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($previewArtisanWhoHasClaimedThisProject["id"]),$fileImageToVisualize,htmlentities($previewArtisanWhoHasClaimedThisProject["name"]." ".$previewArtisanWhoHasClaimedThisProject["surname"]),htmlentities($previewArtisanWhoHasClaimedThisProject["shopName"]),translate("Total products of this artisan").": ".$previewArtisanWhoHasClaimedThisProject["numberOfProductsOfThisArtisan"]);
          endCardGrid();
          $numberCollaboratorsForThisProject = obtainNumberCollaboratorsForThisProject($_GET["id"]);
          if($numberCollaboratorsForThisProject >= 2){
            addParagraph(translate("Collaborators").":");
            $previewArtisansCollaboratorsOfThisProject = obtainPreviewArtisansCollaboratorsOfThisProject($_GET["id"]);
            $previewDesignersCollaboratorsOfThisProject = obtainPreviewDesignersCollaboratorsOfThisProject($_GET["id"]);
            startCardGrid();
            foreach($previewArtisansCollaboratorsOfThisProject as &$singleArtisanPreview){
              $fileImageToVisualize = genericUserImage;
              if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
              }
              addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artisan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
            }
            foreach($previewDesignersCollaboratorsOfThisProject as &$singleDesignerPreview){
              $fileImageToVisualize = genericUserImage;
              if(isset($singleDesignerPreview['icon']) && ($singleDesignerPreview['icon'] != null)){
                $fileImageToVisualize = blobToFile($singleDesignerPreview["iconExtension"],$singleDesignerPreview['icon']);
              }
              addACardForTheGrid("./designer.php?id=".urlencode($singleDesignerPreview["id"]),$fileImageToVisualize,htmlentities($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]),htmlentities(translate("Designer")),"");
            }
            endCardGrid();
          }
        } else {
          upperPartOfThePage(translate("Error"),"");
          addParagraph(translate("You are not a collaborator for the production of this project"));
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
