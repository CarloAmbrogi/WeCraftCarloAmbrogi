<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Global Search
  doInitialScripts();
  upperPartOfThePage(translate("Search"),"./search.php");
  //Content of the page
  addTitle(translate("Global search"));
  $search = "";
  if(isset($_GET["search"])){
    $search = $_GET["search"];
  }
  startFormGet("./gloabalSearch.php");
  addShortTextField(translate("Search"),"search",49);
  endFormGet(translate("SubmitSearch"));
  ?>
    <script>
      //form inserted parameters
      const form = document.querySelector('form');
      const search = document.getElementById('search');
      //Load form fields starting values
      search.value = "<?= $search ?>";
    </script>
  <?php
  //Show search results
  addParagraph(translate("Search results").":");
  $foundAtLeastOneResult = false;
  //Products
  $GlobalSearchPreviewProducts = obtainGlobalSearchPreviewProducts($search);
  startCardGrid();
  foreach($GlobalSearchPreviewProducts as &$singleProductPreview){
    $foundAtLeastOneResult = true;
    $fileImageToVisualize = genericProductImage;
    if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
    }
    $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
    $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"];
    addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,$singleProductPreview["name"],$text1,$text2);
  }
  endCardGrid();
  //Artisans
  $GlobalSearchPreviewArtisans = obtainGlobalSearchPreviewArtisans($search);
  startCardGrid();
  foreach($GlobalSearchPreviewArtisans as &$singleArtisanPreview){
    $foundAtLeastOneResult = true;
    $fileImageToVisualize = genericUserImage;
    if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
    }
    addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
  }
  endCardGrid();
  //Designers
  $GlobalSearchPreviewDesigners = obtainGlobalSearchPreviewDesigners($search);
  startCardGrid();
  foreach($GlobalSearchPreviewDesigners as &$singleDesignerPreview){
    $foundAtLeastOneResult = true;
    $fileImageToVisualize = genericUserImage;
    if(isset($singleDesignerPreview['icon']) && ($singleDesignerPreview['icon'] != null)){
      $fileImageToVisualize = blobToFile($singleDesignerPreview["iconExtension"],$singleDesignerPreview['icon']);
    }
    addACardForTheGrid("./designer.php?id=".urlencode($singleDesignerPreview["id"]),$fileImageToVisualize,htmlentities($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]),translate("Designer"),"");
  }
  endCardGrid();
  //In case of no result
  if($foundAtLeastOneResult == false){
    addParagraphUnsafe("<br>".translate("No result"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
