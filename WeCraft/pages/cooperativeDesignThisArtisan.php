<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperating design this artisan
  //This page show the products in collaboration for the design with the specified artisan
  //This page could work also for designers but the visualizatio of this information for designers is moved in the designer page
  //A check permit to load this page only for artisans
  doInitialScripts();
  if(isset($_GET["id"])){
    if(getKindOfThisAccount($_GET["id"]) != "Artisan"){
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This user is not an artisan"));
    } else {
      //Page ok
      upperPartOfThePage(translate("Artisan cooperative design"),"cookieBack");
      addScriptAddThisPageToCronology();
      $userInfos = obtainUserInfos($_GET["id"]);
      $artisanInfos = obtainArtisanInfos($_GET["id"]);
      $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
      addParagraph(translate("Artisan").": ".$nameAndSurname." (".$artisanInfos["shopName"].")");
      addParagraph(translate("Here will be shown the products for witch this artisan is collaborating with other artisans and designers"));
      //Show the products for whitch this artisan collaborating in a cooperative design
      addButtonOnOffShowHide(translate("Visualise only product of which this artisan is the owner"),"thisArtisanIsntTheOwner");
      $productsForWhitchThisArtisanIsCollaborating = obtainProductsPreviewCooperativeDesign($_GET["id"]);
      startCardGrid();
      foreach($productsForWhitchThisArtisanIsCollaborating as &$singleProductPreview){
        $fileImageToVisualize = genericProductImage;
        if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
          $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
        }
        $text1 = translate("Owner").": ".htmlentities($singleProductPreview["ownerName"])." ".htmlentities($singleProductPreview["ownerSurname"]);
        $text2 = translate("Number of collaborators").": ".$singleProductPreview["numberOfCollaborators"];
        if($_GET["id"] != $singleProductPreview["ownerId"]){
          startDivShowHideMultiple("thisArtisanIsntTheOwner");
        }
        addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["productId"]),$fileImageToVisualize,htmlentities($singleProductPreview["productName"]),$text1,$text2);
        if($_GET["id"] != $singleProductPreview["ownerId"]){
          endDivShowHideMultiple();
        }
      }
      endCardGrid();
      addScriptShowHideMultiple("thisArtisanIsntTheOwner",true);
      forceThisPageReloadWhenBrowserBackButton();
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("You have missed to specify the get id of this artisan"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
