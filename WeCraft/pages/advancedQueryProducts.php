<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  //Advanced query products
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"./advancedQuery.php");
    addScriptAddThisPageToCronology();
    //Content of this page

    addTitle(translate("Advanced query products"));
    
    //Added by an active artisan
    //Price
    //Quantity from the owner
    //Category
    //Added when
    //Last sell when
    //Percentage resell (is set or not) (optionally a range)
    //Number of sells in total
    //Number of sells from the owner
    //Number of extra artisans who sponsor this product
    //Number of extra artisans who sell this product
    //If it is now in cooperation for the production
    //If it is now in cooperation for the production with at least a designer in the group
    //If it is now in cooperation for the production with a certain number of participants in the group
    //If it has been in cooperation for the production
    //Number of tags

    //Form
    startFormGet("./advancedQueryProducts.php");
    addParagraph(translate("The fields you compile will be considered"));
    addParagraph("*".translate("Some fields with min and max values automatically filter that there is at least one"));
    addSelectorWithLabel(translate("Added by an active artisan"),"active",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min price"),"minPrice",49);
    addShortTextField(translate("Max price"),"maxPrice",49);
    addShortTextField(translate("Min quantity from the owner"),"minQuantity",49);
    addShortTextField(translate("Max quantity from the owner"),"maxQuantity",49);
    $categories = categories;
    $categoriesIds = ["","Nonee"];
    $categoriesTra = [translate("Dont specify"),translate("No category")];
    foreach($categories as &$singleCategory){
      array_push($categoriesIds,$singleCategory);
      array_push($categoriesTra,translate($singleCategory));
    }
    addSelectorWithLabel(translate("Category"),"category",$categoriesIds,$categoriesTra);
    addShortTextField(translate("Added from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","addedFrom",49);
    addShortTextField(translate("Added to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","addedTo",49);
    addShortTextField(translate("Last sell from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","lastSellFrom",49);
    addShortTextField(translate("Last sell to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","lastSellTo",49);
    addSelectorWithLabel(translate("Pencertage resell set"),"percentageResellSet",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min pencertage resell"),"minPercentageResell",49);
    addShortTextField(translate("Max pencertage resell"),"maxPercentageResell",49);
    addShortTextField(translate("Min total number of sells"),"minTotalNumberOfSells",49);
    addShortTextField(translate("Max total number of sells"),"maxTotalNumberOfSells",49);
    addShortTextField(translate("Min number of sells from the owner")."*","minNumberOfSellsFromTheOwner",49);
    addShortTextField(translate("Max number of sells from the owner")."*","maxNumberOfSellsFromTheOwner",49);
    addShortTextField(translate("Min number of artisans who sponsor this product"),"minNumberOfArtisansSponsorThisProduct",49);
    addShortTextField(translate("Max number of artisans who sponsor this product"),"maxNumberOfArtisansSponsorThisProduct",49);
    addShortTextField(translate("Min number of extra artisans who sell this product"),"minNumberOfExtraArtisansSellThisProduct",49);
    addShortTextField(translate("Max number of extra artisans who sell this product"),"maxNumberOfExtraArtisansSellThisProduct",49);
    addSelectorWithLabel(translate("In cooperation for the production"),"inCooperationForProduction",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addSelectorWithLabel(translate("In cooperation for the production with at least a designer in the group"),"inCooperationForProductionDesigner",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Min number of participants cooperation production"),"minNumberParticipantsCooperationProduction",49);
    addShortTextField(translate("Max number of participants cooperation production"),"maxNumberParticipantsCooperationProduction",49);
    addSelectorWithLabel(translate("Has been in cooperation for the production"),"hasBeenCooperationForProduction",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    endFormGet(translate("Submit"));
    addButtonLink(translate("Clean research"),"./advancedQueryProducts.php");
    //Load previous inserted values in the form
    ?>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const active = document.getElementById('active');
        const minPrice = document.getElementById('minPrice');
        const maxPrice = document.getElementById('maxPrice');
        const minQuantity = document.getElementById('minQuantity');
        const maxQuantity = document.getElementById('maxQuantity');
        const category = document.getElementById('category');
        const addedFrom = document.getElementById('addedFrom');
        const addedTo = document.getElementById('addedTo');
        const lastSellFrom = document.getElementById('lastSellFrom');
        const lastSellTo = document.getElementById('lastSellTo');
        const percentageResellSet = document.getElementById('percentageResellSet');
        const minPercentageResell = document.getElementById('minPercentageResell');
        const maxPercentageResell = document.getElementById('maxPercentageResell');
        const minTotalNumberOfSells = document.getElementById('minTotalNumberOfSells');
        const maxTotalNumberOfSells = document.getElementById('maxTotalNumberOfSells');
        const minNumberOfSellsFromTheOwner = document.getElementById('minNumberOfSellsFromTheOwner');
        const maxNumberOfSellsFromTheOwner = document.getElementById('maxNumberOfSellsFromTheOwner');
        const minNumberOfArtisansSponsorThisProduct = document.getElementById('minNumberOfArtisansSponsorThisProduct');
        const maxNumberOfArtisansSponsorThisProduct = document.getElementById('maxNumberOfArtisansSponsorThisProduct');
        const minNumberOfExtraArtisansSellThisProduct = document.getElementById('minNumberOfExtraArtisansSellThisProduct');
        const maxNumberOfExtraArtisansSellThisProduct = document.getElementById('maxNumberOfExtraArtisansSellThisProduct');
        const inCooperationForProduction = document.getElementById('inCooperationForProduction');
        const inCooperationForProductionDesigner = document.getElementById('inCooperationForProductionDesigner');
        const minNumberParticipantsCooperationProduction = document.getElementById('minNumberParticipantsCooperationProduction');
        const maxNumberParticipantsCooperationProduction = document.getElementById('maxNumberParticipantsCooperationProduction');
        const hasBeenCooperationForProduction = document.getElementById('hasBeenCooperationForProduction');

        //Load form fields starting values
        active.value = "<?= $_GET["active"] ?>";
        minPrice.value = "<?= $_GET["minPrice"] ?>";
        maxPrice.value = "<?= $_GET["maxPrice"] ?>";
        minQuantity.value = "<?= $_GET["minQuantity"] ?>";
        maxQuantity.value = "<?= $_GET["maxQuantity"] ?>";
        category.value = "<?= $_GET["category"] ?>";
        addedFrom.value = "<?= $_GET["addedFrom"] ?>";
        addedTo.value = "<?= $_GET["addedTo"] ?>";
        lastSellFrom.value = "<?= $_GET["lastSellFrom"] ?>";
        lastSellTo.value = "<?= $_GET["lastSellTo"] ?>";
        percentageResellSet.value = "<?= $_GET["percentageResellSet"] ?>";
        minPercentageResell.value = "<?= $_GET["minPercentageResell"] ?>";
        maxPercentageResell.value = "<?= $_GET["maxPercentageResell"] ?>";
        minTotalNumberOfSells.value = "<?= $_GET["minTotalNumberOfSells"] ?>";
        maxTotalNumberOfSells.value = "<?= $_GET["maxTotalNumberOfSells"] ?>";
        minNumberOfSellsFromTheOwner.value = "<?= $_GET["minNumberOfSellsFromTheOwner"] ?>";
        maxNumberOfSellsFromTheOwner.value = "<?= $_GET["maxNumberOfSellsFromTheOwner"] ?>";
        minNumberOfArtisansSponsorThisProduct.value = "<?= $_GET["minNumberOfArtisansSponsorThisProduct"] ?>";
        maxNumberOfArtisansSponsorThisProduct.value = "<?= $_GET["maxNumberOfArtisansSponsorThisProduct"] ?>";
        minNumberOfExtraArtisansSellThisProduct.value = "<?= $_GET["minNumberOfExtraArtisansSellThisProduct"] ?>";
        maxNumberOfExtraArtisansSellThisProduct.value = "<?= $_GET["maxNumberOfExtraArtisansSellThisProduct"] ?>";
        inCooperationForProduction.value = "<?= $_GET["inCooperationForProduction"] ?>";
        inCooperationForProductionDesigner.value = "<?= $_GET["inCooperationForProductionDesigner"] ?>";
        minNumberParticipantsCooperationProduction.value = "<?= $_GET["minNumberParticipantsCooperationProduction"] ?>";
        maxNumberParticipantsCooperationProduction.value = "<?= $_GET["maxNumberParticipantsCooperationProduction"] ?>";
        hasBeenCooperationForProduction.value = "<?= $_GET["hasBeenCooperationForProduction"] ?>";
      </script>
    <?php
    //sql
    $sqlInitial = "select `Product`.`id`,`Product`.`name`,`Product`.`icon`,`Product`.`iconExtension`,`Product`.`category`,`Product`.`price`,`Product`.`quantity`,COALESCE(sum(`ContentPurchase`.`quantity`),0) as numSells from `Product` left join `ContentPurchase` on `Product`.`id` = ContentPurchase.`product` where 1 ";
    $sqlMid = "";
    $sqlFinal = " group by `Product`.`id` order by `Product`.`id` DESC;";
    if($_GET["active"] == "y"){
      $sqlMid.=" and `Product`.`artisan` in (select `id` from `User` where `isActive` = 1) ";
    }
    if($_GET["active"] == "n"){
      $sqlMid.=" and `Product`.`artisan` in (select `id` from `User` where `isActive` = 0) ";
    }
    if($_GET["minPrice"] != ""){
      $sqlMid.=" and `Product`.`price` >= ".$_GET["minPrice"]." ";
    }
    if($_GET["maxPrice"] != ""){
      $sqlMid.=" and `Product`.`price` <= ".$_GET["maxPrice"]." ";
    }
    if($_GET["minQuantity"] != ""){
      $sqlMid.=" and `Product`.`quantity` >= ".$_GET["minQuantity"]." ";
    }
    if($_GET["maxQuantity"] != ""){
      $sqlMid.=" and `Product`.`quantity` <= ".$_GET["maxQuantity"]." ";
    }
    if($_GET["category"] != ""){
      $sqlMid.=" and `Product`.`category` = '".$_GET["category"]."' ";
    }
    if($_GET["addedFrom"] != ""){
      $sqlMid.=" and `Product`.`added` >= '".$_GET["addedFrom"]."' ";
    }
    if($_GET["addedTo"] != ""){
      $sqlMid.=" and `Product`.`added` <= '".$_GET["addedTo"]."' ";
    }
    if($_GET["lastSellFrom"] != ""){
      $sqlMid.=" and `Product`.`lastSell` is not null and `Product`.`lastSell` >= '".$_GET["lastSellFrom"]."' ";
    }
    if($_GET["lastSellTo"] != ""){
      $sqlMid.=" and `Product`.`lastSell` is not null and `Product`.`lastSell` <= '".$_GET["lastSellTo"]."' ";
    }
    if($_GET["percentageResellSet"] == "y"){
      $sqlMid.=" and `Product`.`percentageResell` is not null ";
    }
    if($_GET["percentageResellSet"] == "n"){
      $sqlMid.=" and `Product`.`percentageResell` is null ";
    }
    if($_GET["minPercentageResell"] != ""){
      $sqlMid.=" and `Product`.`percentageResell` is not null and `Product`.`percentageResell` >= ".$_GET["minPercentageResell"]." ";
    }
    if($_GET["maxPercentageResell"] != ""){
      $sqlMid.=" and `Product`.`percentageResell` is not null and `Product`.`percentageResell` <= ".$_GET["maxPercentageResell"]." ";
    }
    if($_GET["minTotalNumberOfSells"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, sum(`quantity`) as n from `ContentPurchase` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `ContentPurchase`))) as t where n >= ".$_GET["minTotalNumberOfSells"].") ";
    }
    if($_GET["maxTotalNumberOfSells"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, sum(`quantity`) as n from `ContentPurchase` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `ContentPurchase`))) as t where n <= ".$_GET["maxTotalNumberOfSells"].") ";
    }
    if($_GET["minNumberOfSellsFromTheOwner"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `ContentPurchase`.`product` as product, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` join `Product` on `ContentPurchase`.`product` = `Product`.`id` where `ContentPurchase`.`artisan` = `Product`.`artisan` group by `ContentPurchase`.`product`)) as t where n >= ".$_GET["minNumberOfSellsFromTheOwner"].") ";
    }
    if($_GET["maxNumberOfSellsFromTheOwner"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `ContentPurchase`.`product` as product, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` join `Product` on `ContentPurchase`.`product` = `Product`.`id` where `ContentPurchase`.`artisan` = `Product`.`artisan` group by `ContentPurchase`.`product`)) as t where n <= ".$_GET["maxNumberOfSellsFromTheOwner"].") ";
    }
    if($_GET["minNumberOfArtisansSponsorThisProduct"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `Advertisement` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `Advertisement`))) as t where n >= ".$_GET["minNumberOfArtisansSponsorThisProduct"].") ";
    }
    if($_GET["maxNumberOfArtisansSponsorThisProduct"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `Advertisement` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `Advertisement`))) as t where n <= ".$_GET["maxNumberOfArtisansSponsorThisProduct"].") ";
    }
    if($_GET["minNumberOfExtraArtisansSellThisProduct"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `ExchangeProduct` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `ExchangeProduct`))) as t where n >= ".$_GET["minNumberOfExtraArtisansSellThisProduct"].") ";
    }
    if($_GET["maxNumberOfExtraArtisansSellThisProduct"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `ExchangeProduct` group by `product`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `ExchangeProduct`))) as t where n <= ".$_GET["maxNumberOfExtraArtisansSellThisProduct"].") ";
    }
    if($_GET["inCooperationForProduction"] == "y"){
      $sqlMid.=" and `Product`.`id` in (select `product` from `CooperativeProductionProducts`) ";
    }
    if($_GET["inCooperationForProduction"] == "n"){
      $sqlMid.=" and `Product`.`id` not in (select `product` from `CooperativeProductionProducts`) ";
    }
    if($_GET["inCooperationForProductionDesigner"] == "y"){
      $sqlMid.=" and `Product`.`id` in (select `product` from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`)) ";
    }
    if($_GET["inCooperationForProductionDesigner"] == "n"){
      $sqlMid.=" and `Product`.`id` not in (select `product` from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`)) ";
    }
    if($_GET["minNumberParticipantsCooperationProduction"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `CooperativeProductionProducts`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `CooperativeProductionProducts`))) as t where n >= ".$_GET["minNumberParticipantsCooperationProduction"].") ";
    }
    if($_GET["maxNumberParticipantsCooperationProduction"] != ""){
      $sqlMid.=" and `Product`.`id` in (select product from ((select `product` as product, count(*) as n from `CooperativeProductionProducts`) union (select `id` as product, 0 as n from `Product` where `id` not in (select `product` from `CooperativeProductionProducts`))) as t where n <= ".$_GET["maxNumberParticipantsCooperationProduction"].") ";
    }
    if($_GET["hasBeenCooperationForProduction"] == "y"){
      $sqlMid.=" and `Product`.`id` in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') ";
    }
    if($_GET["hasBeenCooperationForProduction"] == "n"){
      $sqlMid.=" and `Product`.`id` not in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') ";
    }
    $sql = $sqlInitial.$sqlMid.$sqlFinal;
    //Show results
    $SearchPreviewProducts = executeSqlDoNotShowTheError($sql);
    $numberResults = 0;
    startCardGrid();
    foreach($SearchPreviewProducts as &$singleProductPreview){
      $fileImageToVisualize = genericProductImage;
      if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
      }
      $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
      $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"]." ".translate("Number of sells").": ".$singleProductPreview["numSells"];
      addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);
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
