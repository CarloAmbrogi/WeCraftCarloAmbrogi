<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page to see the partecipants of the cooperation for the design of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be an artisan or a designer
  //You can see this page only if you are collaborating for the design of this product
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProductExists($_GET["id"])){
      if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){        
        //Check you are a collaborator
        if(isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$_GET["id"])){
          addScriptAddThisPageToCronology();
          upperPartOfThePage(translate("Cooperative design"),"cookieBack");
          //Real content of this page
          $productInfos = obtainProductInfos($_GET["id"]);
          addParagraph(translate("Product").": ".$productInfos["name"]);
          $ownerId = $productInfos["artisan"];
          addParagraph(translate("Owner").":");
          startCardGrid();
          $previewArtisanOwner = obtainPreviewThisArtisan($ownerId);
          $fileImageToVisualize = genericUserImage;
          if(isset($previewArtisanOwner['icon']) && ($previewArtisanOwner['icon'] != null)){
            $fileImageToVisualize = blobToFile($previewArtisanOwner["iconExtension"],$previewArtisanOwner['icon']);
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($previewArtisanOwner["id"]),$fileImageToVisualize,htmlentities($previewArtisanOwner["name"]." ".$previewArtisanOwner["surname"]),htmlentities($previewArtisanOwner["shopName"]),translate("Total products of this artsan").": ".$previewArtisanOwner["numberOfProductsOfThisArtisan"]);
          endCardGrid();
          $numberCollaboratorsForThisProduct = obtainNumberCollaboratorsForThisProduct($_GET["id"]);
          if($numberCollaboratorsForThisProduct >= 2){
            addParagraph(translate("Collaborators").":");
            $previewArtisansCollaboratorsOfThisProduct = obtainPreviewArtisansCollaboratorsOfThisProduct($_GET["id"]);
            $previewDesignersCollaboratorsOfThisProduct = obtainPreviewDesignersCollaboratorsOfThisProduct($_GET["id"]);
            startCardGrid();
            foreach($previewArtisansCollaboratorsOfThisProduct as &$singleArtisanPreview){
              $fileImageToVisualize = genericUserImage;
              if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
              }
              addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
            }
            foreach($previewDesignersCollaboratorsOfThisProduct as &$singleDesignerPreview){
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
          addParagraph(translate("You are not a collaborator for the design of this product"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        addParagraph(translate("This page is visible only to artisans and designers"));
      }
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This product doesnt exists"));
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    //You have missed to specify the get param id of the product
    addParagraph(translate("You have missed to specify the get param id of the product"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
