<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the analytics administrator
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Analytics administrator"),"");
    addScriptAddThisPageToCronology();
    //Content of this page
    //Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period
    addParagraph(translate("Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period"));
    $minNumItems = 2;
    $durationLastPeriod = 5184000;
    $previewArtisansWhoAreOnlyResellingItems = obtainPreviewArtisansWhoArePraticallyOnlyResellingItems($minNumItems,$durationLastPeriod);
    startCardGrid();
    foreach($previewArtisansWhoAreOnlyResellingItems as &$singleArtisanPreview){
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),"");
    }
    endCardGrid();
    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
