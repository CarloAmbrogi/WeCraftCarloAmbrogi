<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search Product
  doInitialScripts();
  addScriptAddThisPageToCronology();
  upperPartOfThePage(translate("Search"),"./search.php");
  //Content of the page
  addTitle(translate("Search product"));
  startFormGet("./searchProduct.php");
  addParagraph(translate("The fields you compile will be considered"));
  addShortTextFieldWithLike(translate("Name"),"name","nameL",49);
  addShortTextFieldWithLike(translate("Description"),"description","descriptionL",49);
  addShortTextFieldWithAlsoASelect(translate("Tags")." (".translate("you can insert more tags separated by space character").")","tags",49,translate("At least one of theese tags"),translate("All theese tags"),"tagOr","tagAnd","tagsS");
  startSquare();
  addShortTextField(translate("Min price"),"minPrice",24);
  addShortTextField(translate("Max price"),"maxPrice",24);
  endSquare();
  startSquare();
  addShortTextField(translate("Min quantity from the owner"),"minQuantity",24);
  addShortTextField(translate("Max quantity from the owner"),"maxQuantity",24);
  endSquare();
  ?>
    <label for="formCategory" class="form-label"><?= translate("Category") ?></label>
    <select id="category" name="category">
      <option value="any"><?= translate("Any") ?></option>
      <option value="Nonee"><?= translate("Nonee") ?></option>
        <?php
          $possibleCategories = categories;
          foreach($possibleCategories as &$category){
        ?>
          <option value="<?= $category ?>"><?= translate($category) ?></option>
        <?php
          }
        ?>
    </select>
  <?php
  startSquare();
  addShortTextFieldWithTwoSelectForTimePeriod(translate("Added on WeCraft"),"addedWhen",24,"addedWhenP","addedWhenD");
  endSquare();
  startSquare();
  addShortTextFieldWithTwoSelectForTimePeriod(translate("Last sell"),"lastSell",24,"lastSellP","lastSellD");
  endSquare();
  startSquare();
  addShortTextField(translate("Min number of sells"),"minNumSells",24);
  addShortTextField(translate("Max number of sells"),"maxNumSells",24);
  endSquare();
  addSelector2Options(translate("All theese conditions"),translate("At least one of theese conditions"),"and","or","cond");
  addSelector2Options(translate("Show products from most recent"),translate("Show products from most sold"),"rec","sold","ord");
  endFormGet(translate("SubmitSearch"));
  addButtonLink(translate("Clean research"),"./searchProduct.php");
  //input get params
  $name = "";
  if(isset($_GET["name"])){
    $name = $_GET["name"];
  }
  $name = trim($name);
  $name = removeQuotes($name);
  $nameL = "exactly";
  if(isset($_GET["nameL"])){
    if($_GET["nameL"] == "like"){
      $nameL = "like";
    }
  }
  $description = "";
  if(isset($_GET["description"])){
    $description = $_GET["description"];
  }
  $description = trim($description);
  $description = removeQuotes($description);
  $descriptionL = "exactly";
  if(isset($_GET["descriptionL"])){
    if($_GET["descriptionL"] == "like"){
      $descriptionL = "like";
    }
  }
  if(isset($_GET["tags"])){
    $tags = $_GET["tags"];
  }
  $tags = trim($tags);
  $tags = removeQuotes($tags);
  $tagsS = "tagOr";
  if(isset($_GET["tagsS"])){
    if($_GET["tagsS"] == "tagAnd"){
      $tagsS = "tagAnd";
    }
  }
  $minPrice = "";
  if(isset($_GET["minPrice"])){
    $minPrice = $_GET["minPrice"];
  }
  $minPrice = trim($minPrice);
  $minPrice = removeQuotes($minPrice);
  $maxPrice = "";
  if(isset($_GET["maxPrice"])){
    $maxPrice = $_GET["maxPrice"];
  }
  $maxPrice = trim($maxPrice);
  $maxPrice = removeQuotes($maxPrice);
  $minQuantity = "";
  if(isset($_GET["minQuantity"])){
    $minQuantity = $_GET["minQuantity"];
  }
  $minQuantity = trim($minQuantity);
  $minQuantity = removeQuotes($minQuantity);
  $maxQuantity = "";
  if(isset($_GET["maxQuantity"])){
    $maxQuantity = $_GET["maxQuantity"];
  }
  $maxQuantity = trim($maxQuantity);
  $maxQuantity = removeQuotes($maxQuantity);
  $category = "any";
  if(isset($_GET["category"])){
    if($_GET["category"] == "Nonee"){
      $category = "Nonee";
    }
    $possibleCategories = categories;
    foreach($possibleCategories as &$singleCategory){
      if($_GET["category"] == $singleCategory){
        $category = $singleCategory;
      }
    }
  }
  $addedWhen = "";
  if(isset($_GET["addedWhen"])){
    $addedWhen = $_GET["addedWhen"];
  }
  $addedWhen = trim($addedWhen);
  $addedWhen = removeQuotes($addedWhen);
  $addedWhenP = "inTheLast";
  if(isset($_GET["addedWhenP"])){
    if($_GET["addedWhenP"] == "before"){
      $addedWhenP = "before";
    }
  }
  $addedWhenD = "hours";
  if(isset($_GET["addedWhenD"])){
    if($_GET["addedWhenD"] == "days"){
      $addedWhenD = "days";
    }
    if($_GET["addedWhenD"] == "weeks"){
      $addedWhenD = "weeks";
    }
    if($_GET["addedWhenD"] == "months"){
      $addedWhenD = "months";
    }
    if($_GET["addedWhenD"] == "years"){
      $addedWhenD = "years";
    }
  }
  $lastSell = "";
  if(isset($_GET["lastSell"])){
    $lastSell = $_GET["lastSell"];
  }
  $lastSell = trim($lastSell);
  $lastSell = removeQuotes($lastSell);
  $lastSellP = "inTheLast";
  if(isset($_GET["lastSellP"])){
    if($_GET["lastSellP"] == "before"){
      $lastSellP = "before";
    }
  }
  $lastSellD = "hours";
  if(isset($_GET["lastSellD"])){
    if($_GET["lastSellD"] == "days"){
      $lastSellD = "days";
    }
    if($_GET["lastSellD"] == "weeks"){
      $lastSellD = "weeks";
    }
    if($_GET["lastSellD"] == "months"){
      $lastSellD = "months";
    }
    if($_GET["lastSellD"] == "years"){
      $lastSellD = "years";
    }
  }
  $minNumSells = "";
  if(isset($_GET["minNumSells"])){
    $minNumSells = $_GET["minNumSells"];
  }
  $minNumSells = trim($minNumSells);
  $minNumSells = removeQuotes($minNumSells);
  $maxNumSells = "";
  if(isset($_GET["maxNumSells"])){
    $maxNumSells = $_GET["maxNumSells"];
  }
  $maxNumSells = trim($maxNumSells);
  $maxNumSells = removeQuotes($maxNumSells);
  $cond = "and";
  if(isset($_GET["cond"])){
    if($_GET["cond"] == "or"){
      $cond = "or";
    }
  }
  $ord = "rec";
  if(isset($_GET["ord"])){
    if($_GET["ord"] == "sold"){
      $ord = "sold";
    }
  }
  //Load previous inserted values in the form
  ?>
    <script>
      //form inserted parameters
      const form = document.querySelector('form');
      const name = document.getElementById('name');
      const nameL = document.getElementById('nameL');
      const description = document.getElementById('description');
      const descriptionL = document.getElementById('descriptionL');
      const tags = document.getElementById('tags');
      const tagsS = document.getElementById('tagsS');
      const minPrice = document.getElementById('minPrice');
      const maxPrice = document.getElementById('maxPrice');
      const minQuantity = document.getElementById('minQuantity');
      const maxQuantity = document.getElementById('maxQuantity');
      const category = document.getElementById('category');
      const addedWhen = document.getElementById('addedWhen');
      const addedWhenP = document.getElementById('addedWhenP');
      const addedWhenD = document.getElementById('addedWhenD');
      const lastSell = document.getElementById('lastSell');
      const lastSellP = document.getElementById('lastSellP');
      const lastSellD = document.getElementById('lastSellD');
      const minNumSells = document.getElementById('minNumSells');
      const maxNumSells = document.getElementById('maxNumSells');
      const cond = document.getElementById('cond');
      const ord = document.getElementById('ord');

      //Load form fields starting values
      name.value = "<?= $name ?>";
      nameL.value = "<?= $nameL ?>";
      description.value = "<?= $description ?>";
      descriptionL.value = "<?= $descriptionL ?>";
      tags.value = "<?= $tags ?>";
      tagsS.value = "<?= $tagsS ?>";
      minPrice.value = "<?= $minPrice ?>";
      maxPrice.value = "<?= $maxPrice ?>";
      minQuantity.value = "<?= $minQuantity ?>";
      maxQuantity.value = "<?= $maxQuantity ?>";
      category.value = "<?= $category ?>";
      addedWhen.value = "<?= $addedWhen ?>";
      addedWhenP.value = "<?= $addedWhenP ?>";
      addedWhenD.value = "<?= $addedWhenD ?>";
      lastSell.value = "<?= $lastSell ?>";
      lastSellP.value = "<?= $lastSellP ?>";
      lastSellD.value = "<?= $lastSellD ?>";
      minNumSells.value = "<?= $minNumSells ?>";
      maxNumSells.value = "<?= $maxNumSells ?>";
      cond.value = "<?= $cond ?>";
      ord.value = "<?= $ord ?>";

      function isValidQuantity(quantity){
        const quantityRegex = /^[0-9]+$/;
        return quantityRegex.test(quantity);
      }

      function isValidPrice(price){
        //The price shoud have at least an integer digit and exactly 2 digits after the floating point
        const priceRegex = /^[0-9]+\.[0-9][0-9]$/;
        return priceRegex.test(price);
      }

      //prevent sending form with errors
      form.onsubmit = function(e){
        let YouHaveToprevent = false;
        let messagePrevent = "";
        if(!YouHaveToprevent && minPrice.value !== ""){
          if(!isValidPrice(minPrice.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min price is not empty or is not in the format number plus dot plus two digits") ?>";
          }
        }
        if(!YouHaveToprevent && maxPrice.value !== ""){
          if(!isValidPrice(maxPrice.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The max price is not empty or is not in the format number plus dot plus two digits") ?>";
          }
        }
        if(!YouHaveToprevent && minPrice.value !== "" && maxPrice.value !== ""){
          if(Number(minPrice.value) > Number(maxPrice.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min price is greater than the max price") ?>";
          }
        }
        if(!YouHaveToprevent && minQuantity.value !== ""){
          if(!isValidQuantity(minQuantity.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min quantity is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && maxQuantity.value !== ""){
          if(!isValidQuantity(maxQuantity.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The max quantity is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && minQuantity.value !== "" && maxQuantity.value !== ""){
          if(Number(minQuantity.value) > Number(maxQuantity.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min quantity is greater than the max quantity") ?>";
          }
        }
        if(!YouHaveToprevent && addedWhen.value !== ""){
          if(!isValidQuantity(addedWhen.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The added on WeCraft value is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && lastSell.value !== ""){
          if(!isValidQuantity(lastSell.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The last sell value is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && minNumSells.value !== ""){
          if(!isValidQuantity(minNumSells.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min number of sells is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && maxNumSells.value !== ""){
          if(!isValidQuantity(maxNumSells.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The max number of sells is not empty or is not a number") ?>";
          }
        }
        if(!YouHaveToprevent && minNumSells.value !== "" && maxNumSells.value !== ""){
          if(Number(minNumSells.value) > Number(maxNumSells.value)){
            YouHaveToprevent = true;
            messagePrevent = "<?= translate("The min number of sells is greater than the max number of sells") ?>";
          }
        }
        if(YouHaveToprevent){
          e.preventDefault();
          alert(messagePrevent);
        }
      }
    </script>
  <?php
  //Exclude setted values witch should be numbers but they aren't
  if($minPrice != ""){
    if(!isValidFloatNumber($minPrice)){
      $minPrice = "";
    }
  }
  if($maxPrice != ""){
    if(!isValidFloatNumber($maxPrice)){
      $maxPrice = "";
    }
  }
  if($minQuantity != ""){
    if(!isValidQuantity($minQuantity)){
      $minQuantity = "";
    }
  }
  if($maxQuantity != ""){
    if(!isValidQuantity($maxQuantity)){
      $maxQuantity = "";
    }
  }
  if($addedWhen != ""){
    if(!isValidQuantity($addedWhen)){
      $addedWhen = "";
    }
  }
  if($lastSell != ""){
    if(!isValidQuantity($lastSell)){
      $lastSell = "";
    }
  }
  if($minNumSells != ""){
    if(!isValidQuantity($minNumSells)){
      $minNumSells = "";
    }
  }
  if($maxNumSells != ""){
    if(!isValidQuantity($maxNumSells)){
      $maxNumSells = "";
    }
  }
  //Last sell shouldn't be 0
  if($lastSell != ""){
    if($lastSell == 0){
      $lastSell = "";
    }
  }
  //In case you set the last sell, the number of sells should be at least 1
  if($lastSell != ""){
    if($maxNumSells >= 1){
      if($minNumSells <= 1){
        $minNumSells = 1;
      }
    }
  }
  //Research results
  if($name != "" || $description != "" || $tags != "" || $minPrice != "" || $maxPrice != "" || $minQuantity != "" || $maxQuantity != "" || $category != "any" || $addedWhen != "" || $lastSell != "" || $minNumSells != "" || $maxNumSells != ""){
    addSubtopicIndex("researchResults");
    addTitle(translate("Research results").":");
    ?>
      <script>
        window.location = "#researchResults";
      </script>
    <?php
    //Prepare sql
    $sqlInitialPart = "select id,name,iconExtension,icon,price,quantity,category,numSells from (select `Product`.`id` as id,`Product`.`name` as name,`Product`.`iconExtension` as iconExtension,`Product`.`icon` as icon,`Product`.`price` as price,`Product`.`quantity` as quantity,`Product`.`category` as category, COALESCE(sum(`ContentPurchase`.`quantity`),0) as numSells from `Product` left join `ContentPurchase` on `Product`.`id` = `ContentPurchase`.`product` where ";
    $sqlMidExample = "`Product`.`name` = 'PIPPO' and `Product`.`description` = 'PIPPO' and `Product`.`id` in (select `ProductTags`.`productId` from `ProductTags` where `ProductTags`.`tag` = 'PIPPO' or `ProductTags`.`tag` = 'PIPPO') and (`Product`.`price` >= 1111 and `Product`.`price` <= 9999) and (`Product`.`quantity` >= 1111 and `Product`.`quantity` <= 9999) and `Product`.`category` = 'PIPPO' and ((TIMESTAMPDIFF(SECOND, `Product`.`added`, CURRENT_TIMESTAMP()) <= 1000) and (TIMESTAMPDIFF(SECOND, `Product`.`added`, CURRENT_TIMESTAMP()) >= 1000)) and ((TIMESTAMPDIFF(SECOND, `Product`.`lastSell`, CURRENT_TIMESTAMP()) <= 1000) and (TIMESTAMPDIFF(SECOND, `Product`.`lastSell`, CURRENT_TIMESTAMP()) >= 1000)) ";
    $sqlFinalPartExample = "group by `Product`.`id` ORDER BY `id` DESC limit 100) as t where numSells >= 1111 and numSells <= 9999;";
    $sqlFinalPart = "group by `Product`.`id` ORDER BY `id` DESC,numSells DESC limit 100) as t";
    $sqlFinalPartPrecNumSells = "group by `Product`.`id` ORDER BY numSells DESC,`id` DESC limit 100) as t";
    $exampleOptionalFinalPart = " where numSells >= 1111 and numSells <= 9999";
    $endSemiColumn = ";";
    if($ord == "sold"){
      $sqlFinalPart = $sqlFinalPartPrecNumSells;//Order from most sold instead of from most recent
    }
    $sql = "1 ";
    if($cond == "or"){
      $sql = "0 ";
    }
    $optionalFinalPart = "";
    //name and description
    if($name != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Product`.`name` ";
      if($nameL == "exactly"){
        $sql.="= '".$name."' ";
      } else {
        $sql.="like '%".$name."%' ";
      }
    }
    if($description != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Artisan`.`description` ";
      if($descriptionL == "exactly"){
        $sql.="= '".$description."' ";
      } else {
        $sql.="like '%".$description."%' ";
      }
    }
    //tags
    $tagCond = "or";
    if($tagsS == "tagAnd"){
      $tagCond = "and";
    }
    $tagsList = explode(" ", $tags);
    $firstTimeTag = true;
    $aTag = false;
    foreach($tagsList as &$singlePossibleTag){
      $singleTag = $singlePossibleTag;
      $singleTag = trim($singleTag);
      if($singleTag != ""){
        $aTag = true;
        if($firstTimeTag == true){
          $firstTimeTag = false;
          if($sql != ""){
            $sql.=$cond." (";
          } else {
            $sql.="(";
          }
          $sql.="`Product`.`id` in (select `ProductTags`.`productId` from `ProductTags` where ";
        } else {
          $sql.=$tagCond." "."`Product`.`id` in (select `ProductTags`.`productId` from `ProductTags` where ";
        }
        $sql.="`ProductTags`.`tag` = '".$singleTag."') ";
      }
    }
    if($aTag == true){
      $sql = substr($sql, 0, -1);
      $sql.=") ";
    }
    //(`Product`.`price` >= 1111 and `Product`.`price` <= 9999)
    if($minPrice != "" || $maxPrice != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="(";
      if($minPrice != ""){
        $sql.="`Product`.`price` >= ".$minPrice;
      }
      if($minPrice != "" && $maxPrice != ""){
        $sql.=" and ";
      }
      if($maxPrice != ""){
        $sql.="`Product`.`price` <= ".$maxPrice;
      }
      $sql.=") ";
    }
    //(`Product`.`quantity` >= 1111 and `Product`.`quantity` <= 9999)
    if($minQuantity != "" || $maxQuantity != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="(";
      if($minQuantity != ""){
        $sql.="`Product`.`quantity` >= ".$minQuantity;
      }
      if($minQuantity != "" && $maxQuantity != ""){
        $sql.=" and ";
      }
      if($maxQuantity != ""){
        $sql.="`Product`.`quantity` <= ".$maxQuantity;
      }
      $sql.=") ";
    }
    //category
    if($category != "any"){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Product`.`category` ";
      $sql.="= '".$category."' ";
    }
    //added when
    if($addedWhen != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $operand = "<=";
      if($addedWhenP == "before"){
        $operand = ">=";
      }
      $multiplier = 60 * 60;
      if($addedWhenD == "days"){
        $multiplier = $multiplier * 24;
      }
      if($addedWhenD == "weeks"){
        $multiplier = $multiplier * 24 * 7;
      }
      if($addedWhenD == "months"){
        $multiplier = $multiplier * 24 * 7 * 30;
      }
      if($addedWhenD == "years"){
        $multiplier = $multiplier * 24 * 365;
      }
      $value = $addedWhen * $multiplier;
      $sql.="(TIMESTAMPDIFF(SECOND, `Product`.`added`, CURRENT_TIMESTAMP()) ".$operand." ".$value.") ";
    }
    //lastSell
    if($lastSell != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $operand = "<=";
      if($lastSellP == "before"){
        $operand = ">=";
      }
      $multiplier = 60 * 60;
      if($lastSellD == "days"){
        $multiplier = $multiplier * 24;
      }
      if($lastSellD == "weeks"){
        $multiplier = $multiplier * 24 * 7;
      }
      if($lastSellD == "months"){
        $multiplier = $multiplier * 24 * 7 * 30;
      }
      if($lastSellD == "years"){
        $multiplier = $multiplier * 24 * 365;
      }
      $value = $lastSell * $multiplier;
      $sql.="(TIMESTAMPDIFF(SECOND, `Product`.`lastSell`, CURRENT_TIMESTAMP()) ".$operand." ".$value.") ";
    }
    //$exampleOptionalFinalPart = " where numSells >= 1111 and numSells <= 9999";
    if($minNumSells != "" || $maxNumSells != ""){
      $optionalFinalPart.=" where ";
      if($minNumSells != ""){
        $optionalFinalPart.="numSells >= ".$minNumSells;
      }
      if($minNumSells != "" && $maxNumSells != ""){
        $optionalFinalPart.=" and ";
      }
      if($maxNumSells != ""){
        $optionalFinalPart.="numSells <= ".$maxNumSells;
      }
    }
    $sql = $sqlInitialPart.$sql.$sqlFinalPart.$optionalFinalPart.$endSemiColumn;
    //Show results
    $SearchPreviewProducts = executeSql($sql);
    startCardGrid();
    $foundAtLeastOneResult = false;
    foreach($SearchPreviewProducts as &$singleProductPreview){
      if(isThisProductOfAnActiveArtisan($singleProductPreview["id"])){
        $foundAtLeastOneResult = true;
        $fileImageToVisualize = genericProductImage;
        if(isset($singleProductPreview['icon']) && ($singleProductPreview['icon'] != null)){
          $fileImageToVisualize = blobToFile($singleProductPreview["iconExtension"],$singleProductPreview['icon']);
        }
        $text1 = translate("Category").": ".translate($singleProductPreview["category"]).'<br>'.translate("Price").": ".floatToPrice($singleProductPreview["price"]);
        $text2 = translate("Quantity available from the owner").": ".$singleProductPreview["quantity"]." ".translate("Number of sells").": ".$singleProductPreview["numSells"];
        addACardForTheGrid("./product.php?id=".urlencode($singleProductPreview["id"]),$fileImageToVisualize,htmlentities($singleProductPreview["name"]),$text1,$text2);  
      }
    }
    endCardGrid();
    //In case of no result
    if($foundAtLeastOneResult == false){
      addParagraphUnsafe("<br>".translate("No result"));
    }
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
