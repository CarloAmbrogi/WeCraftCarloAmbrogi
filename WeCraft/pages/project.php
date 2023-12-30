<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a project by id (the id is sent as a get)
  //A project is visible only to realated customer, designers and artisans
  //Actions for designers:
  // edit the project
  // assign artisans
  // refuse artisans
  //Actions for artisans
  // refuse the project
  // claim the project
  //Actions for customers
  // refuse artisans
  // confirm the project

  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProjectExists($_GET["id"])){
      //Check possibility to see this project
      if(doesThisUserCanSeeThisProject($_SESSION["userId"],$_GET["id"])){
        addScriptAddThisPageToCronology();
        upperPartOfThePage(translate("Project"),"cookieBack");
        //Real content of this page
        //General info of this project
        $projectInfos = obtainProjectInfos($_GET["id"]);
        startRow();
        startCol();
        $fileImageToVisualizeProduct = genericProjectImage;
        $isIconToThisProjectSetted = false;
        if(isset($projectInfos['icon']) && ($projectInfos['icon'] != null)){
          $isIconToThisProjectSetted = true;
          $fileImageToVisualizeProduct = blobToFile($projectInfos["iconExtension"],$projectInfos['icon']);
        }
        addLateralImageLow($fileImageToVisualizeProduct,$nameAndSurname);
        endCol();
        startCol();
        addTitle($projectInfos["name"]);
        addParagraph(translate("Price").": ".floatToPrice($projectInfos["price"]));
        addParagraph(translate("Percentage to the designer").": ".floatToPrice($projectInfos["percentageToDesigner"]));
        endCol();
        endRow();
        addParagraphUnsafe(adjustTextWithYouTubeLinks($projectInfos["description"]));
        //Carousel with images of this project
        addCarouselImagesOfThisProject($_GET["id"]);
        //related designer
        addParagraph(translate("This project has been created by this designer").":");
        $designerUserInfos = obtainUserInfos($projectInfos["designer"]);
        $fileImageToVisualize = genericUserImage;
        if(isset($designerUserInfos['icon']) && ($designerUserInfos['icon'] != null)){
          $fileImageToVisualize = blobToFile($designerUserInfos["iconExtension"],$designerUserInfos['icon']);
        }
        addACard("./designer.php?id=".urlencode($designerUserInfos["id"]),$fileImageToVisualize,htmlentities($designerUserInfos["name"]." ".$designerUserInfos["surname"]),translate("Designer"),"");
        //related customer
        addParagraph(translate("This project has been created for this customer").":");
        $customerUserInfos = obtainUserInfos($projectInfos["customer"]);
        $fileImageToVisualize = genericUserImage;
        if(isset($customerUserInfos['icon']) && ($customerUserInfos['icon'] != null)){
          $fileImageToVisualize = blobToFile($customerUserInfos["iconExtension"],$customerUserInfos['icon']);
        }
        addACard("./AAAAAAAAAAAAAA.php?id=".urlencode($customerUserInfos["id"]),$fileImageToVisualize,htmlentities($customerUserInfos["name"]." ".$customerUserInfos["surname"]),translate("Customer"),"");
        //related artisans
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
          addParagraph(translate("This project has been claimed by this artisan"));
          $artisanUserInfos = obtainUserInfos($projectInfos["claimedByThisArtisan"]);
          $artisanArtisanInfos = obtainArtisanInfos($projectInfos["claimedByThisArtisan"]);
          $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($projectInfos["claimedByThisArtisan"]);
          $fileImageToVisualize = genericUserImage;
          if(isset($artisanUserInfos['icon']) && ($artisanUserInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($artisanUserInfos["iconExtension"],$artisanUserInfos['icon']);
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($artisanUserInfos["id"]),$fileImageToVisualize,htmlentities($artisanUserInfos["name"]." ".$artisanUserInfos["surname"]),htmlentities($artisanArtisanInfos["shopName"]),translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
        } else {
          $numberArtisansAssignedThisProject = numberArtisansAssignedThisProject($_GET["id"]);
          if($numberArtisansAssignedThisProject > 0){
            addParagraph(translate("This project is assigned to theese artisans").":");
            $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($_GET["id"]);
            startCardGrid();
            $foundAResult = false;
            foreach($previewArtisansToWitchIsAssignedThisProject as &$singleArtisanPreview){
              $fileImageToVisualize = genericUserImage;
              if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
              }
              addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
            }
            endCardGrid();
          } else {
            addParagraph(translate("The designer hasnt already assigned this project to artisans"));
          }
        }
        //Confimation by the customer
        $thisProjectIsConfirmed = false;
        if(isset($projectInfos["confirmedByTheCustomer"]) and $projectInfos["confirmedByTheCustomer"] != null){
          $thisProjectIsConfirmed = true;
          addParagraph(translate("This project is confirmed by the customer"));
          addParagraph(translate("Purchased")." ".$projectInfos["timestampPurchase"]);
          addParagraph(translate("Address")." ".$projectInfos["address"]);
        }
        //Completed ready
        if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
          addParagraph(translate("This project is completed and ready from")." ".$projectInfos["timestampReady"]);
        }
        //Commands for the designer
        if($kindOfTheAccountInUse == "Designer"){
          if(!$thisProjectIsConfirmed){
            addButtonLink(translate("Edit project"),"./editProject.php?id=".urlencode($_GET["id"]));
            if($isIconToThisProjectSetted){
              addButtonLink(translate("Delete project icon"),"./deleteProjectIcon.php?id=".urlencode($_GET["id"]));
            } else {
              addButtonLink(translate("Edit project icon"),"./editProjectIcon.php?id=".urlencode($_GET["id"]));
            }
            addButtonLink(translate("Add images to this project"),"./addImagesToThisProject.php?id=".urlencode($_GET["id"]));
            addButtonLink(translate("Remove images to this project"),"./removeImagesToThisProject.php?id=".urlencode($_GET["id"]));
          }
          if(!$isTheProjectClaimed){
            addButtonLink(translate("Assign artisans to this project"),"./assignArtisansToThisProject.php?id=".urlencode($_GET["id"]));
          }
          if(!$thisProjectIsConfirmed){
            addButtonLink(translate("Refuse artisans to this project"),"./refuseArtisansToThisProject.php?id=".urlencode($_GET["id"]));
          }
        }
        //Commands for the artisans
        if($kindOfTheAccountInUse == "Artisan"){
          if(!$isTheProjectClaimed){
            addButtonLink(translate("Claim this project"),"./claimThisProject.php?id=".urlencode($_GET["id"]));
          }
          if(!$thisProjectIsConfirmed){
            addButtonLink(translate("Refuse this project"),"./refuseThisProject.php?id=".urlencode($_GET["id"]));
          }
        }
        //Commands for the customer
        if($kindOfTheAccountInUse == "Customer"){
          if(!$thisProjectIsConfirmed){
            addButtonLink(translate("Refuse artisans to this project"),"./refuseArtisansToThisProject.php?id=".urlencode($_GET["id"]));
            addButtonLink(translate("Confirm this project"),"./confirmThisProject.php?id=".urlencode($_GET["id"]));
          }
        }
        //End main content of this page
      } else {
        upperPartOfThePage(translate("Error"),"");
        addParagraph(translate("You havent access to this project"));
      }
    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This project doesnt exists"));
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("You have missed to specify the get param id of the project"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
