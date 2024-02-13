<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  //Advanced query projects
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"./advancedQuery.php");
    addScriptAddThisPageToCronology();
    //Content of this page

    addTitle(translate("Advanced query projects"));
    
    //Added by an active designer
    //Price
    //Percentage to designer
    //State
    //Public or private
    //Timestamp purchase
    //Timestamp ready
    //If it is now in cooperation for the production
    //If it is now in cooperation for the production with at least a designer in the group
    //If it is now in cooperation for the production with a certain number of participants in the group
    //If it has been in cooperation for the production
    //Estimated duration
    //Completed and in time

    //Form
    startFormGet("./advancedQueryProjects.php");
    addParagraph(translate("The fields you compile will be considered"));
    addParagraph("*".translate("Some fields with min and max values automatically filter that there is at least one"));
    addSelectorWithLabel(translate("Added by an active artisan"),"active",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min price"),"minPrice",49);
    addShortTextField(translate("Max price"),"maxPrice",49);
    addShortTextField(translate("Min percentage to designer"),"minPercentageDesigner",49);
    addShortTextField(translate("Min percentage to designer"),"maxPercentageDesigner",49);
    addSelectorWithLabel(translate("State"),"state",["","Created","Presented","Claimed","Confirmed","Completed"],[translate("Dont specify"),translate("Created"),translate("Presented"),translate("Claimed"),translate("Confirmed"),translate("Completed")]);
    addSelectorWithLabel(translate("Public"),"public",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Confirmed from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","purchasedFrom",49);
    addShortTextField(translate("Confirmed to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","purchasedTo",49);
    addShortTextField(translate("Ready from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","readyFrom",49);
    addShortTextField(translate("Ready to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","readyTo",49);
    addSelectorWithLabel(translate("In cooperation for the production"),"inCooperationForProduction",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addSelectorWithLabel(translate("In cooperation for the production with at least a designer in the group"),"inCooperationForProductionDesigner",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min number of participants cooperation production"),"minNumberParticipantsCooperationProduction",49);
    addShortTextField(translate("Max number of participants cooperation production"),"maxNumberParticipantsCooperationProduction",49);
    addSelectorWithLabel(translate("Has been in cooperation for the production"),"hasBeenCooperationForProduction",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min estimated time")." (".translate("seconds").")","minEstimatedTime",49);
    addShortTextField(translate("Max estimated time")." (".translate("seconds").")","maxEstimatedTime",49);
    addSelectorWithLabel(translate("Completed and in time"),"completedInTime",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    endFormGet(translate("Submit"));
    addButtonLink(translate("Clean research"),"./advancedQueryProjects.php");
    //Load previous inserted values in the form
    ?>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const active = document.getElementById('active');
        const minPrice = document.getElementById('minPrice');
        const maxPrice = document.getElementById('maxPrice');
        const minPercentageDesigner = document.getElementById('minPercentageDesigner');
        const maxPercentageDesigner = document.getElementById('maxPercentageDesigner');
        const state = document.getElementById('state');
        const public = document.getElementById('public');
        const purchasedFrom = document.getElementById('purchasedFrom');
        const purchasedTo = document.getElementById('purchasedTo');
        const readyFrom = document.getElementById('readyFrom');
        const readyTo = document.getElementById('readyTo');
        const inCooperationForProduction = document.getElementById('inCooperationForProduction');
        const inCooperationForProductionDesigner = document.getElementById('inCooperationForProductionDesigner');
        const minNumberParticipantsCooperationProduction = document.getElementById('minNumberParticipantsCooperationProduction');
        const maxNumberParticipantsCooperationProduction = document.getElementById('maxNumberParticipantsCooperationProduction');
        const hasBeenCooperationForProduction = document.getElementById('hasBeenCooperationForProduction');
        const minEstimatedTime = document.getElementById('minEstimatedTime');
        const maxEstimatedTime = document.getElementById('maxEstimatedTime');
        const completedInTime = document.getElementById('completedInTime');

        //Load form fields starting values
        active.value = "<?= $_GET["active"] ?>";
        minPrice.value = "<?= $_GET["minPrice"] ?>";
        maxPrice.value = "<?= $_GET["maxPrice"] ?>";
        minPercentageDesigner.value = "<?= $_GET["minPercentageDesigner"] ?>";
        maxPercentageDesigner.value = "<?= $_GET["maxPercentageDesigner"] ?>";
        state.value = "<?= $_GET["state"] ?>";
        public.value = "<?= $_GET["public"] ?>";
        purchasedFrom.value = "<?= $_GET["purchasedFrom"] ?>";
        purchasedTo.value = "<?= $_GET["purchasedTo"] ?>";
        readyFrom.value = "<?= $_GET["readyFrom"] ?>";
        readyTo.value = "<?= $_GET["readyTo"] ?>";
        inCooperationForProduction.value = "<?= $_GET["inCooperationForProduction"] ?>";
        inCooperationForProductionDesigner.value = "<?= $_GET["inCooperationForProductionDesigner"] ?>";
        minNumberParticipantsCooperationProduction.value = "<?= $_GET["minNumberParticipantsCooperationProduction"] ?>";
        maxNumberParticipantsCooperationProduction.value = "<?= $_GET["maxNumberParticipantsCooperationProduction"] ?>";
        hasBeenCooperationForProduction.value = "<?= $_GET["hasBeenCooperationForProduction"] ?>";
        minEstimatedTime.value = "<?= $_GET["minEstimatedTime"] ?>";
        maxEstimatedTime.value = "<?= $_GET["maxEstimatedTime"] ?>";
        completedInTime.value = "<?= $_GET["completedInTime"] ?>";
      </script>
    <?php
    //sql
    $sqlInitial = "select `Project`.`id`,`Project`.`name`,`Project`.`icon`,`Project`.`iconExtension`,`Project`.`price`,`Project`.`percentageToDesigner` from `Project` where 1 ";
    $sqlMid = "";
    $sqlFinal = " order by `Project`.`id` DESC;";
    if($_GET["active"] == "y"){
      $sqlMid.=" and `Project`.`designer` in (select `id` from `User` where `isActive` = 1) ";
    }
    if($_GET["active"] == "n"){
      $sqlMid.=" and `Project`.`designer` in (select `id` from `User` where `isActive` = 0) ";
    }
    if($_GET["minPrice"] != ""){
      $sqlMid.=" and `Project`.`price` >= ".$_GET["minPrice"]." ";
    }
    if($_GET["maxPrice"] != ""){
      $sqlMid.=" and `Project`.`price` <= ".$_GET["maxPrice"]." ";
    }
    if($_GET["minPercentageDesigner"] != ""){
      $sqlMid.=" and `Project`.`percentageToDesigner` >= ".$_GET["minPercentageDesigner"]." ";
    }
    if($_GET["maxPercentageDesigner"] != ""){
      $sqlMid.=" and `Project`.`percentageToDesigner` <= ".$_GET["maxPercentageDesigner"]." ";
    }
    if($_GET["state"] == "Created"){
      $sqlMid.=" and `Project`.`id` not in (select `project` from `ProjectAssignArtisans`) ";
    }
    if($_GET["state"] == "Presented"){
      $sqlMid.=" and `Project`.`id` in (select `project` from `ProjectAssignArtisans`) and `Project`.`claimedByThisArtisan` is null ";
    }
    if($_GET["state"] == "Claimed"){
      $sqlMid.=" and `Project`.`claimedByThisArtisan` is not null and `Project`.`confirmedByTheCustomer` = 0 ";
    }
    if($_GET["state"] == "Confirmed"){
      $sqlMid.=" and `Project`.`confirmedByTheCustomer` = 1 and `Project`.`timestampReady` is null ";
    }
    if($_GET["state"] == "Completed"){
      $sqlMid.=" and `Project`.`timestampReady` is not null ";
    }
    if($_GET["public"] == "y"){
      $sqlMid.=" and `Project`.`isPublic` = 1 ";
    }
    if($_GET["public"] == "n"){
      $sqlMid.=" and `Project`.`isPublic` = 0 ";
    }
    if($_GET["purchasedFrom"] != ""){
      $sqlMid.=" and `Project`.`timestampPurchase` is not null and `Project`.`timestampPurchase` >= '".$_GET["purchasedFrom"]."' ";
    }
    if($_GET["purchasedTo"] != ""){
      $sqlMid.=" and `Project`.`timestampPurchase` is not null and `Project`.`timestampPurchase` <= '".$_GET["purchasedTo"]."' ";
    }
    if($_GET["readyFrom"] != ""){
      $sqlMid.=" and `Project`.`timestampReady` is not null and `Project`.`timestampReady` >= '".$_GET["readyFrom"]."' ";
    }
    if($_GET["readyTo"] != ""){
      $sqlMid.=" and `Project`.`timestampReady` is not null and `Project`.`timestampReady` <= '".$_GET["readyTo"]."' ";
    }
    if($_GET["inCooperationForProduction"] == "y"){
      $sqlMid.=" and `Project`.`id` in (select `project` from `CooperativeProductionProjects`) ";
    }
    if($_GET["inCooperationForProduction"] == "n"){
      $sqlMid.=" and `Project`.`id` not in (select `project` from `CooperativeProductionProjects`) ";
    }
    if($_GET["inCooperationForProductionDesigner"] == "y"){
      $sqlMid.=" and `Project`.`id` in (select `project` from `CooperativeProductionProjects` where `user` in (select `id` from `Designer`)) ";
    }
    if($_GET["inCooperationForProductionDesigner"] == "n"){
      $sqlMid.=" and `Project`.`id` not in (select `project` from `CooperativeProductionProjects` where `user` in (select `id` from `Designer`)) ";
    }
    if($_GET["minNumberParticipantsCooperationProduction"] != ""){
      $sqlMid.=" and `Project`.`id` in (select project from ((select `project` as project, count(*) as n from `CooperativeProductionProjects`) union (select `id` as project, 0 as n from `Project` where `id` not in (select `project` from `CooperativeProductionProjects`))) as t where n >= ".$_GET["minNumberParticipantsCooperationProduction"].") ";
    }
    if($_GET["maxNumberParticipantsCooperationProduction"] != ""){
      $sqlMid.=" and `Project`.`id` in (select project from ((select `project` as project, count(*) as n from `CooperativeProductionProjects`) union (select `id` as project, 0 as n from `Project` where `id` not in (select `project` from `CooperativeProductionProjects`))) as t where n <= ".$_GET["maxNumberParticipantsCooperationProduction"].") ";
    }
    if($_GET["hasBeenCooperationForProduction"] == "y"){
      $sqlMid.=" and `Project`.`id` in (select `project` from `CooperativeProductionProjectsTrig` where `action` = 'insert') ";
    }
    if($_GET["hasBeenCooperationForProduction"] == "n"){
      $sqlMid.=" and `Project`.`id` not in (select `project` from `CooperativeProductionProjectsTrig` where `action` = 'insert') ";
    }
    if($_GET["minEstimatedTime"] != ""){
      $sqlMid.=" and `Project`.`estimatedTime` >= ".$_GET["minEstimatedTime"]." ";
    }
    if($_GET["maxEstimatedTime"] != ""){
      $sqlMid.=" and `Project`.`estimatedTime` <= ".$_GET["maxEstimatedTime"]." ";
    }
    if($_GET["completedInTime"] == "y"){
      $sqlMid.=" and `Project`.`timestampReady` is not null and `Project`.`timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `Project`.`timestampPurchase`, `Project`.`timestampReady`) > `Project`.`estimatedTime`) ";
    }
    if($_GET["completedInTime"] == "n"){
      $sqlMid.=" and not(`Project`.`timestampReady` is not null and `Project`.`timestampPurchase` is not null and (TIMESTAMPDIFF(SECOND, `Project`.`timestampPurchase`, `Project`.`timestampReady`) < `Project`.`estimatedTime`)) ";
    }
    $sql = $sqlInitial.$sqlMid.$sqlFinal;
    //Show results
    $SearchPreviewProjects = executeSqlDoNotShowTheError($sql);
    $numberResults = 0;
    startCardGrid();
    foreach($SearchPreviewProjects as &$singleProjectPreview){
      $fileImageToVisualize = genericProjectImage;
      if(isset($singleProjectPreview['icon']) && ($singleProjectPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProjectPreview["iconExtension"],$singleProjectPreview['icon']);
      }
      $text1 = translate("Price").": ".floatToPrice($singleProjectPreview["price"]);
      $text2 = translate("Percentage to designer").": ".$singleProjectPreview["percentageToDesigner"]."%";
      addACardForTheGrid("./project.php?id=".urlencode($singleProjectPreview["id"]),$fileImageToVisualize,htmlentities($singleProjectPreview["name"]),$text1,$text2);
      $numberResults++;
    }
    endCardGrid();
    addParagraph(translate("Number of results").": ".$numberResults);

    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
