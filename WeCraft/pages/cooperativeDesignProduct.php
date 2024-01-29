<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for the collaboration for a product (get param id is te id of the product related to this collaboration)
  //You need to be an artisan or a designer
  //You can see this page only if you are collaborating for the design of this product
  //If you are the owner of the product you can add partecipants and delete this collaboration
  //In this page there is a collaboration sheet
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProductExists($_GET["id"])){
      if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer" || $_SESSION["userId"] == "admin"){        
        //Check you are a collaborator
        if(isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$_GET["id"]) || $_SESSION["userId"] == "admin"){
          addScriptAddThisPageToCronology();
          upperPartOfThePage(translate("Cooperative design"),"cookieBack");
          //Real content of this page
          $productInfos = obtainProductInfos($_GET["id"]);
          //Show in the left col the related product and in the right col some options related to the collaboration
          startRow();
          startCol();
          //Show the related product
          addParagraph(translate("Product").":");
          $fileImageToVisualize = genericProductImage;
          if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
          }
          $text1 = translate("Category").": ".translate($productInfos["category"]).'<br>'.translate("Price").": ".floatToPrice($productInfos["price"]);
          $text2 = translate("Quantity from the owner").": ".$productInfos["quantity"];
          addACardForTheGrid("./product.php?id=".urlencode($productInfos["id"]),$fileImageToVisualize,htmlentities($productInfos["name"]),$text1,$text2);
          endCol();
          startCol();
          //Show some options related to the collaboration
          if($_SESSION["userId"] == $productInfos["artisan"]){
            //Options in case you are the owner
            addButtonLink(translate("Add partecipants"),"./addPartecipantsCooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
            addButtonLink(translate("Remove partecipants"),"./removePartecipantsCooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
            addButtonLink(translate("Coordinate collaboration"),"./");
            addButtonLink(translate("Delete this collaboration"),"./deleteCooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
          } else {
            //Options in case you aren't the owner
            addButtonLink(translate("Leave the group"),"./leaveGroupCooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
          }
          //Options for every collaborator
          addButtonLink(translate("Send message"),"./chat.php?chatKind=".urlencode("product")."&chatWith=".urlencode($_GET["id"]));
          addButtonLink(translate("See partecipants"),"./seePartecipantsCooperativeDesignProduct.php?id=".urlencode($_GET["id"]));
          endCol();
          endRow();
          //Show the sheet
          addTitle(translate("Sheet"));
          $sheetContent = obtainSheetContentProducts($_GET["id"]);
          if(isset($sheetContent["lastUpdateFrom"]) && $sheetContent["lastUpdateFrom"] != null){
            addParagraph(translate("Last update")." ".$sheetContent["lastUpdateWhen"]." ".translate("froms")." ".$sheetContent["name"]." ".$sheetContent["surname"]." (".$sheetContent["email"].")");
          } else {
            addParagraph(translate("Sheet created in")." ".$sheetContent["lastUpdateWhen"]);
          }
          startForm1();
          startForm2("./sendDataToSheetForProducts.php");
          addLongTextField("","insertedSheet",100000);
          addHiddenField("insertedProductId",$_GET["id"]);
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
          forceThisPageReloadWhenBrowserBackButton();
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
