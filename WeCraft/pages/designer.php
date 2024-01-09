<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page of this designer
  //This page permits to see a designer with its informations specifying the id of the designer with a get
  //If this page is viewed without a get by a designer, the designer automatically is redirected to its designer page
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(getKindOfThisAccount($_GET["id"]) != "Designer"){
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This user is not a designer"));
    } else {
      //Page ok
      upperPartOfThePage(translate("Designer"),"cookieBack");
      addScriptAddThisPageToCronology();
      //Show the designer
      $userInfos = obtainUserInfos($_GET["id"]);
      $designerInfos = obtainDesignerInfos($_GET["id"]);
      startRow();
      startCol();
      $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
      $fileImageToVisualize = genericUserImage;
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
      }
      addLateralImage($fileImageToVisualize,$nameAndSurname);
      endCol();
      startCol();
      addParagraphWithoutMb3($nameAndSurname);
      addEmailToLinkWithoutMb3($userInfos["email"]);
      $textButtonShowHide = translate("Show or hide more information about this designer");
      if($_GET["id"] == $_SESSION["userId"]){
        $textButtonShowHide = translate("Show or hide more information about you");
      }
      addButtonOnOffShowHide($textButtonShowHide,"moreInformationOnThisDesigner");
      if($kindOfTheAccountInUse != "Guest" && $_GET["id"] != $_SESSION["userId"]){
        //Send message to this designer
        addButtonLink(translate("Send message"),"./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($_GET["id"]));
      }
      endCol();
      endRow();
      startDivShowHide("moreInformationOnThisDesigner");
      addParagraphWithoutMb3Unsafe(adjustTextWithYouTubeLinks($designerInfos["description"]));
      addCarouselImagesOfThisUser($_GET["id"]);
      endDivShowHide("moreInformationOnThisDesigner");
      //Show the products in collaboration for the design with this designer
      addParagraph(translate("Here will be shown the products for witch this designer is collaborating with other artisans and designers"));
      $productsForWhitchThisDesignerIsCollaborating = obtainProductsPreviewCooperativeDesign($_GET["id"]);
      $foundOne = false;
      startCardGrid();
      foreach($productsForWhitchThisDesignerIsCollaborating as &$singleProductPreview){
        $foundOne = true;
        $fileImageToVisualize = genericProductImage;
        if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
          $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
        }
        $text1 = translate("Owner").": ".htmlentities($singleProductPreview["ownerName"])." ".htmlentities($singleProductPreview["ownerSurname"]);
        $text2 = translate("Number of collaborators").": ".$singleProductPreview["numberOfCollaborators"];
        addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["productId"]),$fileImageToVisualize,htmlentities($singleProductPreview["productName"]),$text1,$text2);
      }
      endCardGrid();
      if($foundOne == false){
        addParagraph(translate("No result"));
      }
    }
  } else {
    if($kindOfTheAccountInUse == "Designer"){
      //Redirect to this page of the designer
      header('Location: ./designer.php?id='.urlencode($_SESSION["userId"]));
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This page is visible only to designers"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
