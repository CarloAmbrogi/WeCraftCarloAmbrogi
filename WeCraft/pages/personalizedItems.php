<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Personalized items
  //This page is available for designers, artisans and customers and it changes the functionality according to the account in use
  //This is the default page for designers when they do the log in and they can reach it directly from the tab bar
  //other users can reach this page from My WeCraft section
  //This page is not available for guests
  //How does it works?
  // A designer add a project of a product and present it to a customer (notification to the user) (this action starts from the chat with the user)
  // The designer can present to the project one or more artisans (send notification to that artisans)
  // The realization of the project by the artisan starts when the project is claimed by an artisan and the customer has confirmed
  // The first artisan whitch will accept (an artisan can also decline) the projcet, claims it and the others will be discarded (notification to the customer)
  // A customer can confirm only if the project is claimed by an artisan (in that moment he pay and he send the order)
  // If the artisan start a cooperation for the production, the designer and the other artisans will be suggested to be added in the group
  // When the product is ready, the artisan can send a notification to the customer and the delivery start
  //Designers can see:
  // a section for projects not already presented to artisans (editable)
  // a section for projects presented to at least one artisan (editable)
  // a section for projects claimed from the artisan (editable; if edited, it needs to be reclamed)
  // a section for projects confirmed by the customer
  // a section for projects completed ready
  // a section with public unclaimed projects
  // a section with public completed projects
  //Artisans can see:
  // a section for projects presented to him
  // a section for projects claimed by him
  // a section for projects confirmed by the customer
  // a section for projects completed ready
  // a section with public unclaimed projects
  //Customers can see:
  // a section for projects not yet claimed
  // a section for claimed projects (he can confirm)
  // a section for projects confirmed by him
  // a section for projects completed ready
  //Adin can see:
  // all the projects per state
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Personalized items"),"");
  //Check you are not a guest
  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Customer" || $kindOfTheAccountInUse == "Designer" || $_SESSION["userId"] == "admin"){
    addScriptAddThisPageToChronology();
    //Page for designers
    if($kindOfTheAccountInUse == "Designer"){
      addParagraph(translate("In this page there are your projects for the personalized items"));
      addParagraph(translate("You can add a project for a personalized item from the chat with a customer"));
      addParagraph(translate("Select witch category of projects you want to see").":");
      startFormGet($_SERVER['PHP_SELF']);
      ?>
        <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
        <select id="insertedCategory" name="insertedCategory">
          <option value="v1" <?php if($_GET["insertedCategory"] == "v1"){echo "selected";} ?>><?= translate("Projects not already presented to an artisan") ?></option>
          <option value="v2" <?php if($_GET["insertedCategory"] == "v2"){echo "selected";} ?>><?= translate("Projects presented to at least one artisan") ?></option>
          <option value="v3" <?php if($_GET["insertedCategory"] == "v3"){echo "selected";} ?>><?= translate("Projects claimed from an artisan") ?></option>
          <option value="v4" <?php if($_GET["insertedCategory"] == "v4"){echo "selected";} ?>><?= translate("Projects confirmed by the customer") ?></option>
          <option value="v5" <?php if($_GET["insertedCategory"] == "v5"){echo "selected";} ?>><?= translate("Projects completed and ready") ?></option>
          <option value="v6" <?php if($_GET["insertedCategory"] == "v6"){echo "selected";} ?>><?= translate("Public unclaimed projects") ?></option>
          <option value="v7" <?php if($_GET["insertedCategory"] == "v7"){echo "selected";} ?>><?= translate("Public completed projects") ?></option>
        </select>
      <?php
      endFormGet(translate("Load projects"));
      if(isset($_GET["insertedCategory"])){
        $projectsToVisualize = [];
        switch($_GET["insertedCategory"]){
          case "v1":
            //Projects not already presented to an artisan
            addParagraph(translate("Projects not already presented to an artisan"));
            $projectsToVisualize = obtainProjectsPreviewOfThisDesignerNotAssigned($_SESSION["userId"]);
            break;
          case "v2":
            //Projects presented to at least one artisan
            addParagraph(translate("Projects presented to at least one artisan"));
            $projectsToVisualize = obtainProjectsPreviewOfThisDesignerAssigned($_SESSION["userId"]);
            break;
          case "v3":
            //Projects claimed from an artisan
            addParagraph(translate("Projects claimed from an artisan"));
            $projectsToVisualize = obtainProjectsPreviewOfThisDesignerClaimed($_SESSION["userId"]);
            break;
          case "v4":
            //Projects confirmed by the customer
            addParagraph(translate("Projects confirmed by the customer"));
            $projectsToVisualize = obtainProjectsPreviewOfThisDesignerConfirmed($_SESSION["userId"]);
            break;
          case "v5":
            //Projects completed and ready
            addParagraph(translate("Projects completed and ready"));
            $projectsToVisualize = obtainProjectsPreviewOfThisDesignerCompleted($_SESSION["userId"]);
            break;
          case "v6":
            //Public unclaimed projects
            addParagraph(translate("Public unclaimed projects"));
            $projectsToVisualize = obtainPreviewPublicUnclaimedProjects();
            break;
          case "v7":
            //Public completed projects
            addParagraph(translate("Public completed projects"));
            $projectsToVisualize = obtainPreviewPublicCompletedProjects();
            break;
          default:
            addParagraph(translate("You havent selected any category"));
            break;
        }
        //Visualize the projects $projectsToVisualize
        startCardGrid();
        foreach($projectsToVisualize as &$singleProjectPreview){
          $fileImageToVisualize = genericProjectImage;
          if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
          }
          $text1 = translate("Price").": ".floatToPrice($singleProjectPreview["price"]);
          $text2 = translate("Percentage to designer").": ".$singleProjectPreview["percentageToDesigner"]."%";
          addACardForTheGrid("./project.php?id=".urlencode($singleProjectPreview["id"]),$fileImageToVisualize,htmlentities($singleProjectPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      } else {
        addParagraph(translate("You havent selected any category"));
      }
    }
    //Page for artisans
    if($kindOfTheAccountInUse == "Artisan"){
      addParagraph(translate("In this page there are the projects assigned to you for the personalized items"));
      addParagraph(translate("You can claim a project presented to you and wait the confimation by the customer to start realizing it"));
      addParagraph(translate("Select witch category of projects you want to see").":");
      startFormGet($_SERVER['PHP_SELF']);
      ?>
        <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
        <select id="insertedCategory" name="insertedCategory">
          <option value="v1" <?php if($_GET["insertedCategory"] == "v1"){echo "selected";} ?>><?= translate("Projects presented to you") ?></option>
          <option value="v2" <?php if($_GET["insertedCategory"] == "v2"){echo "selected";} ?>><?= translate("Projects claimed by you") ?></option>
          <option value="v3" <?php if($_GET["insertedCategory"] == "v3"){echo "selected";} ?>><?= translate("Projects confirmed by the customer") ?></option>
          <option value="v4" <?php if($_GET["insertedCategory"] == "v4"){echo "selected";} ?>><?= translate("Projects completed ready") ?></option>
          <option value="v5" <?php if($_GET["insertedCategory"] == "v5"){echo "selected";} ?>><?= translate("Public unclaimed projects") ?></option>
        </select>
      <?php
      endFormGet(translate("Load projects"));
      if(isset($_GET["insertedCategory"])){
        $projectsToVisualize = [];
        switch($_GET["insertedCategory"]){
          case "v1":
            //Projects presented to you
            addParagraph(translate("Projects presented to you"));
            $projectsToVisualize = obtainProjectsPreviewAssignedToThisArtisan($_SESSION["userId"]);
            break;
          case "v2":
            //Projects claimed by you
            addParagraph(translate("Projects claimed by you"));
            $projectsToVisualize = obtainProjectsPreviewClaimedByThisArtisan($_SESSION["userId"]);
            break;
          case "v3":
            //Projects confirmed by the customer
            addParagraph(translate("Projects confirmed by the customer"));
            $projectsToVisualize = obtainProjectsPreviewOfThisArtisanConfirmed($_SESSION["userId"]);
            break;
          case "v4":
            //Projects completed ready
            addParagraph(translate("Projects completed ready"));
            $projectsToVisualize = obtainProjectsPreviewOfThisArtisanCompleted($_SESSION["userId"]);
            break;
          case "v5":
            //Public unclaimed projects
            addParagraph(translate("Public unclaimed projects"));
            $projectsToVisualize = obtainPreviewPublicUnclaimedProjects();
            break;
          default:
            addParagraph(translate("You havent selected any category"));
            break;
        }
        //Visualize the projects $projectsToVisualize
        startCardGrid();
        foreach($projectsToVisualize as &$singleProjectPreview){
          $fileImageToVisualize = genericProjectImage;
          if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
          }
          $text1 = translate("Price").": ".floatToPrice($singleProjectPreview["price"]);
          $text2 = translate("Percentage to designer").": ".$singleProjectPreview["percentageToDesigner"]."%";
          addACardForTheGrid("./project.php?id=".urlencode($singleProjectPreview["id"]),$fileImageToVisualize,htmlentities($singleProjectPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      } else {
        addParagraph(translate("You havent selected any category"));
      }
    }
    //Page for customers
    if($kindOfTheAccountInUse == "Customer"){
      addParagraph(translate("In this page there are the projects about the personalized items that the designers has created for you"));
      addParagraph(translate("To request a personalized item ask a designer via chat to create a project for you"));
      addParagraph(translate("If a project is claimed by an artisan you can confirm it and pay"));
      addParagraph(translate("Select witch category of projects you want to see").":");
      startFormGet($_SERVER['PHP_SELF']);
      ?>
        <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
        <select id="insertedCategory" name="insertedCategory">
          <option value="v1" <?php if($_GET["insertedCategory"] == "v1"){echo "selected";} ?>><?= translate("Projects not yet claimed") ?></option>
          <option value="v2" <?php if($_GET["insertedCategory"] == "v2"){echo "selected";} ?>><?= translate("Claimed projects") ?></option>
          <option value="v3" <?php if($_GET["insertedCategory"] == "v3"){echo "selected";} ?>><?= translate("Projects confirmed by you") ?></option>
          <option value="v4" <?php if($_GET["insertedCategory"] == "v4"){echo "selected";} ?>><?= translate("Projects completed ready") ?></option>
        </select>
      <?php
      endFormGet(translate("Load projects"));
      if(isset($_GET["insertedCategory"])){
        $projectsToVisualize = [];
        switch($_GET["insertedCategory"]){
          case "v1":
            //Projects not yet claimed
            addParagraph(translate("Projects not yet claimed"));
            $projectsToVisualize = obtainProjectsPreviewNotClaimedThisCustomer($_SESSION["userId"]);
            break;
          case "v2":
            //Claimed projects
            addParagraph(translate("Claimed projects"));
            $projectsToVisualize = obtainProjectsPreviewClaimedThisCustomer($_SESSION["userId"]);
            break;
          case "v3":
            //Projects confirmed by you
            addParagraph(translate("Projects confirmed by you"));
            $projectsToVisualize = obtainProjectsPreviewOfThisCustomerConfirmed($_SESSION["userId"]);
            break;
          case "v4":
            //Projects completed ready
            addParagraph(translate("Projects completed ready"));
            $projectsToVisualize = obtainProjectsPreviewOfThisCustomerCompleted($_SESSION["userId"]);
            break;
          default:
            addParagraph(translate("You havent selected any category"));
            break;
        }
        //Visualize the projects $projectsToVisualize
        startCardGrid();
        foreach($projectsToVisualize as &$singleProjectPreview){
          $fileImageToVisualize = genericProjectImage;
          if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
          }
          $text1 = translate("Price").": ".floatToPrice($singleProjectPreview["price"]);
          $text2 = translate("Percentage to designer").": ".$singleProjectPreview["percentageToDesigner"]."%";
          addACardForTheGrid("./project.php?id=".urlencode($singleProjectPreview["id"]),$fileImageToVisualize,htmlentities($singleProjectPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      } else {
        addParagraph(translate("You havent selected any category"));
      }
    }
    //Page for the admin
    if($_SESSION["userId"] == "admin"){
      addParagraph(translate("The admin can see all the projects per state"));
      addParagraph(translate("Select witch category of projects you want to see").":");
      startFormGet($_SERVER['PHP_SELF']);
      ?>
        <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
        <select id="insertedCategory" name="insertedCategory">
          <option value="v1" <?php if($_GET["insertedCategory"] == "v1"){echo "selected";} ?>><?= translate("Projects not already presented to an artisan") ?></option>
          <option value="v2" <?php if($_GET["insertedCategory"] == "v2"){echo "selected";} ?>><?= translate("Projects presented to at least one artisan") ?></option>
          <option value="v3" <?php if($_GET["insertedCategory"] == "v3"){echo "selected";} ?>><?= translate("Projects claimed from an artisan") ?></option>
          <option value="v4" <?php if($_GET["insertedCategory"] == "v4"){echo "selected";} ?>><?= translate("Projects confirmed by the customer") ?></option>
          <option value="v5" <?php if($_GET["insertedCategory"] == "v5"){echo "selected";} ?>><?= translate("Projects completed and ready") ?></option>
          <option value="v6" <?php if($_GET["insertedCategory"] == "v6"){echo "selected";} ?>><?= translate("Public unclaimed projects") ?></option>
          <option value="v7" <?php if($_GET["insertedCategory"] == "v7"){echo "selected";} ?>><?= translate("Public completed projects") ?></option>
        </select>
      <?php
      endFormGet(translate("Load projects"));
      if(isset($_GET["insertedCategory"])){
        $projectsToVisualize = [];
        switch($_GET["insertedCategory"]){
          case "v1":
            //Projects not already presented to an artisan
            addParagraph(translate("Projects not already presented to an artisan"));
            $projectsToVisualize = obtainProjectsPreviewNotAssigned();
            break;
          case "v2":
            //Projects presented to at least one artisan
            addParagraph(translate("Projects presented to at least one artisan"));
            $projectsToVisualize = obtainProjectsPreviewAssigned();
            break;
          case "v3":
            //Projects claimed from an artisan
            addParagraph(translate("Projects claimed from an artisan"));
            $projectsToVisualize = obtainProjectsPreviewClaimed();
            break;
          case "v4":
            //Projects confirmed by the customer
            addParagraph(translate("Projects confirmed by the customer"));
            $projectsToVisualize = obtainProjectsPreviewConfirmed();
            break;
          case "v5":
            //Projects completed and ready
            addParagraph(translate("Projects completed and ready"));
            $projectsToVisualize = obtainProjectsPreviewCompleted();
            break;
          case "v6":
            //Public unclaimed projects
            addParagraph(translate("Public unclaimed projects"));
            $projectsToVisualize = obtainPreviewPublicUnclaimedProjects();
            break;
          case "v7":
            //Public completed projects
            addParagraph(translate("Public completed projects"));
            $projectsToVisualize = obtainPreviewPublicCompletedProjects();
            break;
          default:
            addParagraph(translate("You havent selected any category"));
            break;
        }
        //Visualize the projects $projectsToVisualize
        startCardGrid();
        foreach($projectsToVisualize as &$singleProjectPreview){
          $fileImageToVisualize = genericProjectImage;
          if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
            $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
          }
          $text1 = translate("Price").": ".floatToPrice($singleProjectPreview["price"]);
          $text2 = translate("Percentage to designer").": ".$singleProjectPreview["percentageToDesigner"]."%";
          addACardForTheGrid("./project.php?id=".urlencode($singleProjectPreview["id"]),$fileImageToVisualize,htmlentities($singleProjectPreview["name"]),$text1,$text2);
        }
        endCardGrid();
      } else {
        addParagraph(translate("You havent selected any category"));
      }
    }
  } else {
    addParagraph(translate("This page is available only for customers artisans and designers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
