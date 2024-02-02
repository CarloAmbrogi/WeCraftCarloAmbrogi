<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a project by id (the id is sent as a get)
  //A project is visible only to realated customer, designers and artisans (excepts for public projects)
  //Actions for designers:
  // edit the project
  // assign artisans
  // refuse artisans
  // set public or private a project
  //Actions for artisans
  // refuse the project
  // claim the project
  //Actions for customers
  // refuse artisans
  // confirm the project
  // set private a project

  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProjectExists($_GET["id"])){
      //Check possibility to see this project
      $projectInfos = obtainProjectInfos($_GET["id"]);
      if(doesThisUserCanSeeThisProject($_SESSION["userId"],$_GET["id"]) || ($projectInfos["isPublic"] == 1 && $kindOfTheAccountInUse != "Guest") || $_SESSION["userId"] == "admin"){
        addScriptAddThisPageToCronology();
        upperPartOfThePage(translate("Project"),"cookieBack");
        //Real content of this page
        //General info of this project
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
        addParagraph(translate("Percentage to the designer").": ".$projectInfos["percentageToDesigner"]."%");
        endCol();
        endRow();
        addParagraphUnsafe(adjustTextWithYouTubeLinks($projectInfos["description"]));
        //If the project is public or not
        if($projectInfos["isPublic"] == 1){
          addParagraph(translate("This project is public"));
        } else {
          addParagraph(translate("This project is not public"));
        }
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
        if(doesThisUserCanSeeThisProject($_SESSION["userId"],$_GET["id"]) || $_SESSION["userId"] == "admin"){
          addParagraph(translate("This project has been created for this customer").":");
          $customerUserInfos = obtainUserInfos($projectInfos["customer"]);
          $fileImageToVisualize = genericUserImage;
          if(isset($customerUserInfos['icon']) && ($customerUserInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($customerUserInfos["iconExtension"],$customerUserInfos['icon']);
          }
          addACard("./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($customerUserInfos["id"]),$fileImageToVisualize,htmlentities($customerUserInfos["name"]." ".$customerUserInfos["surname"]." (".$customerUserInfos["email"].")"),translate("Customer"),"");
        }
        //Claimed artisan
        $isTheProjectClaimed = false;
        if(isset($projectInfos["claimedByThisArtisan"]) and $projectInfos["claimedByThisArtisan"] != null){
          $isTheProjectClaimed = true;
          $artisanUserInfos = obtainUserInfos($projectInfos["claimedByThisArtisan"]);
          $artisanArtisanInfos = obtainArtisanInfos($projectInfos["claimedByThisArtisan"]);
          $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($projectInfos["claimedByThisArtisan"]);
          $fileImageToVisualize = genericUserImage;
          if(isset($artisanUserInfos['icon']) && ($artisanUserInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($artisanUserInfos["iconExtension"],$artisanUserInfos['icon']);
          }
          if($_SESSION["userId"] == $artisanUserInfos["id"]){
            addParagraph(translate("This project has been claimed by you").":");
          } else {
            addParagraph(translate("This project has been claimed by this artisan").":");
          }
          addACardForTheGrid("./artisan.php?id=".urlencode($artisanUserInfos["id"]),$fileImageToVisualize,htmlentities($artisanUserInfos["name"]." ".$artisanUserInfos["surname"]),htmlentities($artisanArtisanInfos["shopName"]),translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
        }
        //Confimation by the customer
        $thisProjectIsConfirmed = false;
        if($projectInfos["confirmedByTheCustomer"] == 1){
          $thisProjectIsConfirmed = true;
          addParagraph(translate("This project is confirmed by the customer"));
          if(doesThisUserCanSeeThisProject($_SESSION["userId"],$_GET["id"]) || $_SESSION["userId"] == "admin"){
            addParagraph(translate("Purchased")." ".$projectInfos["timestampPurchase"]);
            addParagraph(translate("Address").": ".$projectInfos["address"]);
          }
        }
        //Other candidate artisans
        $numberArtisansAssignedThisProject = numberArtisansAssignedThisProject($_GET["id"]);
        if(!$thisProjectIsConfirmed){
          if(($numberArtisansAssignedThisProject > 0 && !$isTheProjectClaimed) || ($numberArtisansAssignedThisProject >= 2 && $isTheProjectClaimed)){
            if($isTheProjectClaimed){
              addParagraph(translate("The other artisans which were candidate assigned to this project were").":");
            } else {
              addParagraph(translate("This project is assigned to theese artisans").":");
            }
            $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($_GET["id"]);
            startCardGrid();
            foreach($previewArtisansToWitchIsAssignedThisProject as &$singleArtisanPreview){
              if($singleArtisanPreview["id"] != $projectInfos["claimedByThisArtisan"]){
                $fileImageToVisualize = genericUserImage;
                if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
                  $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
                }
                addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
              }            
            }
            endCardGrid();
          } else {
            if(!$isTheProjectClaimed){
              addParagraph(translate("The designer hasnt already assigned this project to artisans"));
            }
          }
        }
        //This customized item has been created in collaboration with
        $numberCollaboratorsForThisProject = obtainNumberCollaboratorsForThisProject($_GET["id"]);
        if($numberCollaboratorsForThisProject >= 2){
          $numberCollaboratorsForThisProjectToShow = $numberCollaboratorsForThisProject - 1;
          addButtonOnOffShowHide(translate("This customized product has been created in collaboration with")." (".$numberCollaboratorsForThisProjectToShow."):","moreInformationCollaborators");
          startDivShowHide("moreInformationCollaborators");
          addParagraph("");
          $previewArtisansCollaboratorsOfThisProject = obtainPreviewArtisansCollaboratorsOfThisProject($_GET["id"]);
          $previewDesignersCollaboratorsOfThisProject = obtainPreviewDesignersCollaboratorsOfThisProject($_GET["id"]);
          startCardGrid();
          foreach($previewArtisansCollaboratorsOfThisProject as &$singleArtisanPreview){
            $fileImageToVisualize = genericUserImage;
            if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
              $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
            }
            addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
          }
          foreach($previewDesignersCollaboratorsOfThisProject as &$singleDesignerPreview){
            $fileImageToVisualize = genericUserImage;
            if(isset($singleDesignerPreview['icon']) && ($singleDesignerPreview['icon'] != null)){
              $fileImageToVisualize = blobToFile($singleDesignerPreview["iconExtension"],$singleDesignerPreview['icon']);
            }
            addACardForTheGrid("./designer.php?id=".urlencode($singleDesignerPreview["id"]),$fileImageToVisualize,htmlentities($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]),htmlentities(translate("Designer")),"");
          }
          endCardGrid();
          endDivShowHide("moreInformationCollaborators");
        }
        //Completed ready
        $thisProjectIsReady = false;
        if(isset($projectInfos["timestampReady"]) and $projectInfos["timestampReady"] != null){
          $thisProjectIsReady = true;
          addParagraph(translate("This project is completed and ready from")." ".$projectInfos["timestampReady"]);
        }
        //Commands for the designer
        if($kindOfTheAccountInUse == "Designer"){
          if($projectInfos["designer"] == $_SESSION["userId"]){
            if(!$thisProjectIsConfirmed){
              addButtonLink(translate("Edit project"),"./editProject.php?id=".urlencode($_GET["id"]));
              if($isIconToThisProjectSetted){
                addButtonLink(translate("Delete project icon"),"./deleteProjectIcon.php?id=".urlencode($_GET["id"]));
              }
              addButtonLink(translate("Edit project icon"),"./editProjectIcon.php?id=".urlencode($_GET["id"]));
              addButtonLink(translate("Add images to this project"),"./addImagesToThisProject.php?id=".urlencode($_GET["id"]));
              $numberOfImages = getNumberImagesOfThisProject($_GET["id"]);
              if($numberOfImages > 0){
                addButtonLink(translate("Remove images to this project"),"./removeImagesToThisProject.php?id=".urlencode($_GET["id"]));
              }
            }
            if(!$isTheProjectClaimed){
              addButtonLink(translate("Assign artisans to this project"),"./assignArtisansToThisProject.php?id=".urlencode($_GET["id"]));
            }
            if(!$thisProjectIsConfirmed){
              if($numberArtisansAssignedThisProject > 0){
                addButtonLink(translate("Refuse artisans to this project"),"./refuseArtisansToThisProject.php?id=".urlencode($_GET["id"]));
              }
            }
            if($projectInfos["isPublic"] == 0){
              addButtonLink(translate("Set this project public"),"./setThisProjectPublic.php?id=".urlencode($_GET["id"]));
            }
            if($projectInfos["isPublic"] == 1){
              addButtonLink(translate("Set this project private"),"./setThisProjectPrivate.php?id=".urlencode($_GET["id"]));
            }
          }
        }
        //Commands for the artisans
        if($kindOfTheAccountInUse == "Artisan"){
          if(!isThisArtisanAssignedToThisProject($_SESSION["userId"],$_GET["id"]) && $projectInfos["isPublic"] == 1){
            addButtonLink(translate("Assign yourself to this project"),"./assignYourselfToThisProject.php?id=".urlencode($_GET["id"]));
          }
          if(isThisArtisanAssignedToThisProject($_SESSION["userId"],$_GET["id"])){
            if(!$isTheProjectClaimed){
              addButtonLink(translate("Claim this project"),"./claimThisProject.php?id=".urlencode($_GET["id"]));
            }
            if(!$thisProjectIsConfirmed){
              addButtonLink(translate("Refuse this project"),"./refuseThisProject.php?id=".urlencode($_GET["id"]));
            }
          }
          if($thisProjectIsConfirmed && $projectInfos["claimedByThisArtisan"] == $_SESSION["userId"] && !$thisProjectIsReady){
            addButtonLink(translate("Annunce that this personalized item is ready"),"./annunceProjectReady.php?id=".urlencode($_GET["id"]));
            //If you are the artisan who has claimed this project and the project is confirmed you can start or stop the collaboration for the cooperative design
            if($numberCollaboratorsForThisProject == 0){
              addButtonLink(translate("Start collaboration for a cooperative design"),"./startCooperativeDesignForProjects.php?id=".urlencode($_GET["id"]));
            } else {
              addParagraph(translate("You are collaborating in group for the design of this customized product"));
              addButtonLink(translate("See collaboration"),"./cooperativeDesignProject.php?id=".urlencode($_GET["id"]));
            }
          }
          if($projectInfos["claimedByThisArtisan"] == $_SESSION["userId"] && $thisProjectIsReady){
            if($numberCollaboratorsForThisProject > 0){
              addParagraph(translate("You have collaborated in group for the design of this customized product"));
              addButtonLink(translate("See collaboration"),"./cooperativeDesignProject.php?id=".urlencode($_GET["id"]));
            }
          }
        }
        //Commands for the customer
        if($kindOfTheAccountInUse == "Customer"){
          if($projectInfos["customer"] == $_SESSION["userId"]){
            if(!$thisProjectIsConfirmed){
              if($numberArtisansAssignedThisProject > 0){
                addButtonLink(translate("Refuse artisans to this project"),"./refuseArtisansToThisProject.php?id=".urlencode($_GET["id"]));
              }
              if($isTheProjectClaimed && !$thisProjectIsConfirmed){
                addButtonLink(translate("Confirm this project"),"./confirmThisProject.php?id=".urlencode($_GET["id"]));
              }
            }
            if($projectInfos["isPublic"] == 1){
              addButtonLink(translate("Set this project private"),"./setThisProjectPrivate.php?id=".urlencode($_GET["id"]));
            }
          }
        }
        //End main content of this page
      } else {
        upperPartOfThePage(translate("Project"),"cookieBack");
        if($kindOfTheAccountInUse == "Guest"){
          addParagraph(translate("You havent done the log in"));
        } else {
          addParagraph(translate("You havent access to this project"));
        }
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
