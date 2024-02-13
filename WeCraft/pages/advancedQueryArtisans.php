<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  //Advanced query users
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"./advancedQuery.php");
    addScriptAddThisPageToCronology();
    //Content of this page

    addTitle(translate("Advanced query artisans"));
    
    //Email verified
    //Active
    //Time registration

    //Number of products*
    //Number of unit of products*
    //Number of sells* from that artisan
    //Number of sells of his products*
    //Number of sells of his products* from that artisan
    //Number of products* he sponsors
    //Number of products* of other artisans he sells
    //Number of units of products* of other artisans he sells
    //Number of his products* which are sponsored by someone
    //Number of products* of a certain category
    //Number of products* which are now in cooperation for the production
    //Number of products* which are now in cooperation for the production with at least a designer in the group
    //Number of products* which has been in cooperation for the production
    //*consider products added on WeCraft during a certain time range

    //Form
    startFormGet("./advancedQueryArtisans.php");
    addParagraph(translate("The fields you compile will be considered"));
    addParagraph("*".translate("Some fields with min and max values automatically filter that there is at least one"));
    addSelectorWithLabel(translate("Email verified"),"emailVerified",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addSelectorWithLabel(translate("Active"),"active",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Time registration from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","timeRegistrationFrom",49);
    addShortTextField(translate("Time registration to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","timeRegistrationTo",49);
    startSquare();
    addShortTextField(translate("Consider in next filters only products added from")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","consideProductsFrom",49);
    addShortTextField(translate("Consider in next filters only products added to")." (".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss)","consideProductsTo",49);
    endSquare();
    addShortTextField(translate("Min number of products"),"minNumOfProducts",49);
    addShortTextField(translate("Max number of products"),"maxNumOfProducts",49);
    addShortTextField(translate("Min number of units of products"),"minNumOfUnitsProducts",49);
    addShortTextField(translate("Max number of units of products"),"maxNumOfUnitsProducts",49);
    addShortTextField(translate("Min number of sells from this artisan")."*","minNumOfSellsThisArtisan",49);
    addShortTextField(translate("Max number of sells from this artisan")."*","maxNumOfSellsThisArtisan",49);
    addShortTextField(translate("Min number of sells of his products")."*","minNumOfSellsOfHisProduct",49);
    addShortTextField(translate("Max number of sells of his products")."*","maxNumOfSellsOfHisProduct",49);
    addShortTextField(translate("Min number of sells of his products from this artisan")."*","minNumOfSellsOfHisProductThisArtisan",49);
    addShortTextField(translate("Max number of sells of his products from this artisan")."*","maxNumOfSellsOfHisProductThisArtisan",49);
    addShortTextField(translate("Min number of products he sponsors"),"minNumProductsSponsors",49);
    addShortTextField(translate("Max number of products he sponsors"),"maxNumProductsSponsors",49);
    addShortTextField(translate("Min number of products of other artisans he sells"),"minNumProductsExchange",49);
    addShortTextField(translate("Max number of products of other artisans he sells"),"maxNumProductsExchange",49);
    addShortTextField(translate("Min number of units of products of other artisans he sells"),"minNumUnitsProductsExchange",49);
    addShortTextField(translate("Max number of units of products of other artisans he sells"),"maxNumUnitsProductsExchange",49);
    addShortTextField(translate("Min number of his products which are sponsored by someone"),"minNumProductsWhichSponsored",49);
    addShortTextField(translate("Max number of his products which are sponsored by someone"),"maxNumProductsWhichSponsored",49);
    addShortTextField(translate("Min number of his products of this category").": ".translate("No category"),"minNumNoCategory",49);
    addShortTextField(translate("Max number of his products of this category").": ".translate("No category"),"maxNumNoCategory",49);
    $categories = categories;
    foreach($categories as &$singleCategory){
      addShortTextField(translate("Min number of his products of this category").": ".translate($singleCategory),"minNum".removeSpaces($singleCategory),49);
      addShortTextField(translate("Max number of his products of this category").": ".translate($singleCategory),"maxNum".removeSpaces($singleCategory),49);
    }
    addShortTextField(translate("Min number of products which are now in cooperation for the production")."*","minNumProductsInCoopProd",49);
    addShortTextField(translate("Max number of products which are now in cooperation for the production")."*","maxNumProductsInCoopProd",49);
    addShortTextField(translate("Min number of products which are now in cooperation for the production with at least a designer in the group")."*","minNumProductsInCoopProdAtLeastDesigner",49);
    addShortTextField(translate("Max number of products which are now in cooperation for the production with at least a designer in the group")."*","maxNumProductsInCoopProdAtLeastDesigner",49);
    addShortTextField(translate("Min number of products which has been in cooperation for the production")."*","minNumProductsBeenInCoopProd",49);
    addShortTextField(translate("Max number of products which has been in cooperation for the production")."*","maxNumProductsBeenInCoopProd",49);
    endFormGet(translate("Submit"));
    addButtonLink(translate("Clean research"),"./advancedQueryArtisans.php");
    //Load previous inserted values in the form
    ?>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const emailVerified = document.getElementById('emailVerified');
        const active = document.getElementById('active');
        const timeRegistrationFrom = document.getElementById('timeRegistrationFrom');
        const timeRegistrationTo = document.getElementById('timeRegistrationTo');
        const consideProductsFrom = document.getElementById('consideProductsFrom');
        const consideProductsTo = document.getElementById('consideProductsTo');
        const minNumOfProducts = document.getElementById('minNumOfProducts');
        const maxNumOfProducts = document.getElementById('maxNumOfProducts');
        const minNumOfUnitsProducts = document.getElementById('minNumOfUnitsProducts');
        const maxNumOfUnitsProducts = document.getElementById('maxNumOfUnitsProducts');
        const minNumOfSellsThisArtisan = document.getElementById('minNumOfSellsThisArtisan');
        const maxNumOfSellsThisArtisan = document.getElementById('maxNumOfSellsThisArtisan');
        const minNumOfSellsOfHisProduct = document.getElementById('minNumOfSellsOfHisProduct');
        const maxNumOfSellsOfHisProduct = document.getElementById('maxNumOfSellsOfHisProduct');
        const minNumOfSellsOfHisProductThisArtisan = document.getElementById('minNumOfSellsOfHisProductThisArtisan');
        const maxNumOfSellsOfHisProductThisArtisan = document.getElementById('maxNumOfSellsOfHisProductThisArtisan');
        const minNumProductsSponsors = document.getElementById('minNumProductsSponsors');
        const maxNumProductsSponsors = document.getElementById('maxNumProductsSponsors');
        const minNumProductsExchange = document.getElementById('minNumProductsExchange');
        const maxNumProductsExchange = document.getElementById('maxNumProductsExchange');
        const minNumUnitsProductsExchange = document.getElementById('minNumUnitsProductsExchange');
        const maxNumUnitsProductsExchange = document.getElementById('maxNumUnitsProductsExchange');
        const minNumProductsWhichSponsored = document.getElementById('minNumProductsWhichSponsored');
        const maxNumProductsWhichSponsored = document.getElementById('maxNumProductsWhichSponsored');
        const minNumNoCategory = document.getElementById('minNumNoCategory');
        const maxNumNoCategory = document.getElementById('maxNumNoCategory');
        <?php
          foreach($categories as &$singleCategory){
            ?>
              const <?= "minNum".removeSpaces($singleCategory) ?> = document.getElementById('<?= "minNum".removeSpaces($singleCategory) ?>');
              const <?= "maxNum".removeSpaces($singleCategory) ?> = document.getElementById('<?= "maxNum".removeSpaces($singleCategory) ?>');
            <?php
          }
        ?>
        const minNumProductsInCoopProd = document.getElementById('minNumProductsInCoopProd');
        const maxNumProductsInCoopProd = document.getElementById('maxNumProductsInCoopProd');
        const minNumProductsInCoopProdAtLeastDesigner = document.getElementById('minNumProductsInCoopProdAtLeastDesigner');
        const maxNumProductsInCoopProdAtLeastDesigner = document.getElementById('maxNumProductsInCoopProdAtLeastDesigner');
        const minNumProductsBeenInCoopProd = document.getElementById('minNumProductsBeenInCoopProd');
        const maxNumProductsBeenInCoopProd = document.getElementById('maxNumProductsBeenInCoopProd');

        //Load form fields starting values
        emailVerified.value = "<?= $_GET["emailVerified"] ?>";
        active.value = "<?= $_GET["active"] ?>";
        timeRegistrationFrom.value = "<?= $_GET["timeRegistrationFrom"] ?>";
        timeRegistrationTo.value = "<?= $_GET["timeRegistrationTo"] ?>";
        consideProductsFrom.value = "<?= $_GET["consideProductsFrom"] ?>";
        consideProductsTo.value = "<?= $_GET["consideProductsTo"] ?>";
        minNumOfProducts.value = "<?= $_GET["minNumOfProducts"] ?>";
        maxNumOfProducts.value = "<?= $_GET["maxNumOfProducts"] ?>";
        minNumOfUnitsProducts.value = "<?= $_GET["minNumOfUnitsProducts"] ?>";
        maxNumOfUnitsProducts.value = "<?= $_GET["maxNumOfUnitsProducts"] ?>";
        minNumOfSellsThisArtisan.value = "<?= $_GET["minNumOfSellsThisArtisan"] ?>";
        maxNumOfSellsThisArtisan.value = "<?= $_GET["maxNumOfSellsThisArtisan"] ?>";
        minNumOfSellsOfHisProduct.value = "<?= $_GET["minNumOfSellsOfHisProduct"] ?>";
        maxNumOfSellsOfHisProduct.value = "<?= $_GET["maxNumOfSellsOfHisProduct"] ?>";
        minNumOfSellsOfHisProductThisArtisan.value = "<?= $_GET["minNumOfSellsOfHisProductThisArtisan"] ?>";
        maxNumOfSellsOfHisProductThisArtisan.value = "<?= $_GET["maxNumOfSellsOfHisProductThisArtisan"] ?>";
        minNumProductsSponsors.value = "<?= $_GET["minNumProductsSponsors"] ?>";
        maxNumProductsSponsors.value = "<?= $_GET["maxNumProductsSponsors"] ?>";
        minNumProductsExchange.value = "<?= $_GET["minNumProductsExchange"] ?>";
        maxNumProductsExchange.value = "<?= $_GET["maxNumProductsExchange"] ?>";
        minNumUnitsProductsExchange.value = "<?= $_GET["minNumUnitsProductsExchange"] ?>";
        maxNumUnitsProductsExchange.value = "<?= $_GET["maxNumUnitsProductsExchange"] ?>";
        minNumProductsWhichSponsored.value = "<?= $_GET["minNumProductsWhichSponsored"] ?>";
        maxNumProductsWhichSponsored.value = "<?= $_GET["maxNumProductsWhichSponsored"] ?>";
        minNumNoCategory.value = "<?= $_GET["minNumNoCategory"] ?>";
        maxNumNoCategory.value = "<?= $_GET["maxNumNoCategory"] ?>";
        <?php
          foreach($categories as &$singleCategory){
            ?>
              <?= "minNum".removeSpaces($singleCategory) ?>.value = "<?= $_GET["minNum".removeSpaces($singleCategory)] ?>";
              <?= "maxNum".removeSpaces($singleCategory) ?>.value = "<?= $_GET["maxNum".removeSpaces($singleCategory)] ?>";
            <?php
          }
        ?>
        minNumProductsInCoopProd.value = "<?= $_GET["minNumProductsInCoopProd"] ?>";
        maxNumProductsInCoopProd.value = "<?= $_GET["maxNumProductsInCoopProd"] ?>";
        minNumProductsInCoopProdAtLeastDesigner.value = "<?= $_GET["minNumProductsInCoopProdAtLeastDesigner"] ?>";
        maxNumProductsInCoopProdAtLeastDesigner.value = "<?= $_GET["maxNumProductsInCoopProdAtLeastDesigner"] ?>";
        minNumProductsBeenInCoopProd.value = "<?= $_GET["minNumProductsBeenInCoopProd"] ?>";
        maxNumProductsBeenInCoopProd.value = "<?= $_GET["maxNumProductsBeenInCoopProd"] ?>";
      </script>
    <?php
    //sql
    $tableProducts = "select `id`,`artisan`,`name`,`description`,`iconExtension`,`icon`,`price`,`quantity`,`category`,`added`,`lastSell`,`percentageResell` from `Product` where 1 ";
    if($_GET["consideProductsFrom"] != ""){
      $tableProducts.=" and `added` >= '".$_GET["consideProductsFrom"]."' ";
    }
    if($_GET["consideProductsTo"] != ""){
      $tableProducts.=" and `added` <= '".$_GET["consideProductsTo"]."' ";
    }
    $tableProducts = " (".$tableProducts.") as tableProducts ";
    $sqlInitial = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`User`.`isActive`,`Artisan`.`shopName`,count(tableProducts.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join ".$tableProducts." on `User`.`id` = tableProducts.`artisan` where `User`.`id` in (select `id` from `Artisan`) ";
    $sqlMid = "";
    $sqlFinal = " group by `User`.`id` order by `User`.`id` DESC;";
    if($_GET["emailVerified"] == "y"){
      $sqlMid.=" and `User`.`emailVerified` = 1 ";
    }
    if($_GET["emailVerified"] == "n"){
      $sqlMid.=" and `User`.`emailVerified` = 0 ";
    }
    if($_GET["active"] == "y"){
      $sqlMid.=" and `User`.`isActive` = 1 ";
    }
    if($_GET["active"] == "n"){
      $sqlMid.=" and `User`.`isActive` = 0 ";
    }
    if($_GET["timeRegistrationFrom"] != ""){
      $sqlMid.=" and `User`.`timeVerificationCode` >= '".$_GET["timeRegistrationFrom"]."' ";
    }
    if($_GET["timeRegistrationTo"] != ""){
      $sqlMid.=" and `User`.`timeVerificationCode` <= '".$_GET["timeRegistrationTo"]."' ";
    }
    if($_GET["minNumOfProducts"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts."))) as t where n >= ".$_GET["minNumOfProducts"].") ";
    }
    if($_GET["maxNumOfProducts"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts."))) as t where n <= ".$_GET["maxNumOfProducts"].") ";
    }
    if($_GET["minNumOfUnitsProducts"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, sum(`quantity`) as n from ".$tableProducts." group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts."))) as t where n >= ".$_GET["minNumOfUnitsProducts"].") ";
    }
    if($_GET["maxNumOfUnitsProducts"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, sum(`quantity`) as n from ".$tableProducts." group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts."))) as t where n <= ".$_GET["maxNumOfUnitsProducts"].") ";
    }
    if($_GET["minNumOfSellsThisArtisan"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, sum(`quantity`) as n from `ContentPurchase` where `product` in (select `id` from ".$tableProducts.") group by `artisan`) as t where n >= ".$_GET["minNumOfSellsThisArtisan"].") ";
    }
    if($_GET["maxNumOfSellsThisArtisan"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, sum(`quantity`) as n from `ContentPurchase` where `product` in (select `id` from ".$tableProducts.") group by `artisan`) as t where n <= ".$_GET["maxNumOfSellsThisArtisan"].") ";
    }
    if($_GET["minNumOfSellsOfHisProduct"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `ContentPurchase`.`artisan` as artisan, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` where `ContentPurchase`.`product` in (select `id` from ".$tableProducts.") group by `ContentPurchase`.`artisan`) as t where n >= ".$_GET["minNumOfSellsOfHisProduct"].") ";
    }
    if($_GET["maxNumOfSellsOfHisProduct"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `ContentPurchase`.`artisan` as artisan, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` where `ContentPurchase`.`product` in (select `id` from ".$tableProducts.") group by `ContentPurchase`.`artisan`) as t where n <= ".$_GET["maxNumOfSellsOfHisProduct"].") ";
    }
    if($_GET["minNumOfSellsOfHisProductThisArtisan"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `ContentPurchase`.`artisan` as artisan, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` join `Product` on `ContentPurchase`.`product` = `Product`.`id` where `Product`.`artisan` = `ContentPurchase`.`artisan` and `ContentPurchase`.`product` in (select `id` from ".$tableProducts.") group by `ContentPurchase`.`artisan`) as t where n >= ".$_GET["minNumOfSellsOfHisProductThisArtisan"].") ";
    }
    if($_GET["maxNumOfSellsOfHisProductThisArtisan"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `ContentPurchase`.`artisan` as artisan, sum(`ContentPurchase`.`quantity`) as n from `ContentPurchase` join `Product` on `ContentPurchase`.`product` = `Product`.`id` where `Product`.`artisan` = `ContentPurchase`.`artisan` and `ContentPurchase`.`product` in (select `id` from ".$tableProducts.") group by `ContentPurchase`.`artisan`) as t where n <= ".$_GET["maxNumOfSellsOfHisProductThisArtisan"].") ";
    }
    if($_GET["minNumProductsSponsors"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `Advertisement`.`artisan` as artisan, count(*) as n from `Advertisement` where `product` in (select `id` from ".$tableProducts.") group by `Advertisement`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `Advertisement` where `product` in (select `id` from ".$tableProducts.")))) as t where n >= ".$_GET["minNumProductsSponsors"].") ";
    }
    if($_GET["maxNumProductsSponsors"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `Advertisement`.`artisan` as artisan, count(*) as n from `Advertisement` where `product` in (select `id` from ".$tableProducts.") group by `Advertisement`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `Advertisement` where `product` in (select `id` from ".$tableProducts.")))) as t where n <= ".$_GET["maxNumProductsSponsors"].") ";
    }
    if($_GET["minNumProductsExchange"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `ExchangeProduct`.`artisan` as artisan, count(*) as n from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.") group by `ExchangeProduct`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.")))) as t where n >= ".$_GET["minNumProductsExchange"].") ";
    }
    if($_GET["maxNumProductsExchange"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `ExchangeProduct`.`artisan` as artisan, count(*) as n from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.") group by `ExchangeProduct`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.")))) as t where n >= ".$_GET["maxNumProductsExchange"].") ";
    }
    if($_GET["minNumUnitsProductsExchange"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `ExchangeProduct`.`artisan` as artisan, sum(`quantity`) as n from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.") group by `ExchangeProduct`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.")))) as t where n >= ".$_GET["minNumUnitsProductsExchange"].") ";
    }
    if($_GET["maxNumUnitsProductsExchange"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `ExchangeProduct`.`artisan` as artisan, sum(`quantity`) as n from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.") group by `ExchangeProduct`.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from `ExchangeProduct` where `product` in (select `id` from ".$tableProducts.")))) as t where n >= ".$_GET["maxNumUnitsProductsExchange"].") ";
    }
    if($_GET["minNumProductsWhichSponsored"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select tableProducts.`artisan` as artisan, count(*) as n from ".$tableProducts." join `Advertisement` on tableProducts.`id` = `Advertisement`.`product` group by tableProducts.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select tableProducts.`artisan` from ".$tableProducts." join `Advertisement` on tableProducts.`id` = `Advertisement`.`product`))) as t where n >= ".$_GET["minNumProductsWhichSponsored"].") ";
    }
    if($_GET["maxNumProductsWhichSponsored"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select tableProducts.`artisan` as artisan, count(*) as n from ".$tableProducts." join `Advertisement` on tableProducts.`id` = `Advertisement`.`product` group by tableProducts.`artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select tableProducts.`artisan` from ".$tableProducts." join `Advertisement` on tableProducts.`id` = `Advertisement`.`product`))) as t where n >= ".$_GET["maxNumProductsWhichSponsored"].") ";
    }
    if($_GET["minNumNoCategory"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." where `category` = 'Nonee' group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts." where `category` = 'Nonee'))) as t where n >= ".$_GET["minNumNoCategory"].") ";
    }
    if($_GET["maxNumNoCategory"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." where `category` = 'Nonee' group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts." where `category` = 'Nonee'))) as t where n <= ".$_GET["maxNumNoCategory"].") ";
    }
    foreach($categories as &$singleCategory){
      if($_GET["minNum".removeSpaces($singleCategory)] != ""){
        $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." where `category` = '".$singleCategory."' group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts." where `category` = '".$singleCategory."'))) as t where n >= ".$_GET["minNum".removeSpaces($singleCategory)].") ";
      }
      if($_GET["maxNum".removeSpaces($singleCategory)] != ""){
        $sqlMid.=" and `User`.`id` in (select artisan from ((select `artisan` as artisan, count(*) as n from ".$tableProducts." where `category` = '".$singleCategory."' group by `artisan`) union (select `id` as artisan, 0 as n from `Artisan` where `id` not in (select `artisan` from ".$tableProducts." where `category` = '".$singleCategory."'))) as t where n <= ".$_GET["maxNum".removeSpaces($singleCategory)].") ";
      }
    }
    if($_GET["minNumProductsInCoopProd"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProducts`) group by `artisan`) as t where n >= ".$_GET["minNumProductsInCoopProd"].") ";
    }
    if($_GET["maxNumProductsInCoopProd"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProducts`) group by `artisan`) as t where n <= ".$_GET["maxNumProductsInCoopProd"].") ";
    }
    if($_GET["minNumProductsInCoopProdAtLeastDesigner"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`)) group by `artisan`) as t where n >= ".$_GET["minNumProductsInCoopProdAtLeastDesigner"].") ";
    }
    if($_GET["maxNumProductsInCoopProdAtLeastDesigner"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProducts` where `user` in (select `id` from `Designer`)) group by `artisan`) as t where n <= ".$_GET["maxNumProductsInCoopProdAtLeastDesigner"].") ";
    }
    if($_GET["minNumProductsBeenInCoopProd"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') group by `artisan`) as t where n >= ".$_GET["minNumProductsBeenInCoopProd"].") ";
    }
    if($_GET["maxNumProductsBeenInCoopProd"] != ""){
      $sqlMid.=" and `User`.`id` in (select artisan from (select `artisan` as artisan, count(*) as n from ".$tableProducts." where `id` in (select `product` from `CooperativeProductionProductsTrig` where `action` = 'insert') group by `artisan`) as t where n <= ".$_GET["maxNumProductsBeenInCoopProd"].") ";
    }
    $sql = $sqlInitial.$sqlMid.$sqlFinal;
    //Show results
    $SearchPreviewArtisans = executeSqlDoNotShowTheError($sql);
    $numberResults = 0;
    startCardGrid();
    foreach($SearchPreviewArtisans as &$singleArtisanPreview){
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total considered products of this artisan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
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
