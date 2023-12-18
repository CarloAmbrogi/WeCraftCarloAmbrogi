<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search Artisan
  doInitialScripts();
  addScriptAddThisPageToCronology();
  upperPartOfThePage(translate("Search"),"./search.php");
  //Content of the page
  addTitle(translate("Search artisan"));
  startFormGet("./searchArtisan.php");
  addParagraph(translate("The fields you compile will be considered"));
  addShortTextFieldWithLike(translate("Email address"),"email","emailL",49);
  addShortTextFieldWithLike(translate("Name"),"name","nameL",49);
  addShortTextFieldWithLike(translate("Surname"),"surname","surnameL",49);
  addShortTextFieldWithLike(translate("Shop name"),"shopName","shopNameL",49);
  addShortTextFieldWithLike(translate("Phone number"),"phoneNumber","phoneNumberL",49);
  addShortTextFieldWithLike(translate("Description"),"description","descriptionL",49);
  addSelector2Options(translate("All theese conditions"),translate("At least one of theese conditions"),"and","or","cond");
  endFormGet(translate("SubmitSearch"));
  addButtonLink(translate("Clean research"),"./searchArtisan.php");
  //input get params
  $email = "";
  if(isset($_GET["email"])){
    $email = $_GET["email"];
  }
  $email = trim($email);
  $email = removeQuotes($email);
  $emailL = "exactly";
  if(isset($_GET["emailL"])){
    if($_GET["emailL"] == "like"){
      $emailL = "like";
    }
  }
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
  $surname = "";
  if(isset($_GET["surname"])){
    $surname = $_GET["surname"];
  }
  $surname = trim($surname);
  $surname = removeQuotes($surname);
  $surnameL = "exactly";
  if(isset($_GET["surnameL"])){
    if($_GET["surnameL"] == "like"){
      $surnameL = "like";
    }
  }
  $shopName = "";
  if(isset($_GET["shopName"])){
    $shopName = $_GET["shopName"];
  }
  $shopName = trim($shopName);
  $shopName = removeQuotes($shopName);
  $shopNameL = "exactly";
  if(isset($_GET["shopNameL"])){
    if($_GET["shopNameL"] == "like"){
        $shopNameL = "like";
    }
  }
  $phoneNumber = "";
  if(isset($_GET["phoneNumber"])){
    $phoneNumber = $_GET["phoneNumber"];
  }
  $phoneNumber = trim($phoneNumber);
  $phoneNumber = removeQuotes($phoneNumber);
  $phoneNumberL = "exactly";
  if(isset($_GET["phoneNumberL"])){
    if($_GET["phoneNumberL"] == "like"){
        $phoneNumberL = "like";
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
  $cond = "and";
  if(isset($_GET["cond"])){
    if($_GET["cond"] == "or"){
        $cond = "or";
    }
  }
  //Load previous inserted values in the form
  ?>
    <script>
      //form inserted parameters
      const form = document.querySelector('form');
      const email = document.getElementById('email');
      const emailL = document.getElementById('emailL');
      const name = document.getElementById('name');
      const nameL = document.getElementById('nameL');
      const surname = document.getElementById('surname');
      const surnameL = document.getElementById('surnameL');
      const shopName = document.getElementById('shopName');
      const shopNameL = document.getElementById('shopNameL');
      const phoneNumber = document.getElementById('phoneNumber');
      const phoneNumberL = document.getElementById('phoneNumberL');
      const description = document.getElementById('description');
      const descriptionL = document.getElementById('descriptionL');
      const cond = document.getElementById('cond');

      //Load form fields starting values
      email.value = "<?= $email ?>";
      emailL.value = "<?= $emailL ?>";
      name.value = "<?= $name ?>";
      nameL.value = "<?= $nameL ?>";
      surname.value = "<?= $surname ?>";
      surnameL.value = "<?= $surnameL ?>";
      shopName.value = "<?= $shopName ?>";
      shopNameL.value = "<?= $shopNameL ?>";
      phoneNumber.value = "<?= $phoneNumber ?>";
      phoneNumberL.value = "<?= $phoneNumberL ?>";
      description.value = "<?= $description ?>";
      descriptionL.value = "<?= $descriptionL ?>";
      cond.value = "<?= $cond ?>";
    </script>
  <?php
  //Research results
  if($email != "" || $name != "" || $surname != "" || $shopName != "" || $phoneNumber != "" || $description != ""){
    addSubtopicIndex("researchResults");
    addTitle(translate("Research results").":");
    ?>
      <script>
        window.location = "#researchResults";
      </script>
    <?php
    //Prepare sql
    $sqlInitialPart = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where ";
    $sqlMidExample = "`User`.`email` like ? or `User`.`name` like ? or `User`.`surname` like ? or concat(`User`.`name`,' ',`User`.`surname`) like ? or concat(`User`.`surname`,' ',`User`.`name`) like ? or `Artisan`.`shopName` like ? or `Artisan`.`description` like ? or `Artisan`.`address` like ? or `Artisan`.`phoneNumber` like ? ";
    $sqlFinalPart = "group by `User`.`id` order by `User`.`id` limit 100;";
    $sql = "";
    if($email != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`User`.`email` ";
      if($emailL == "exactly"){
        $sql.="= '".$email."' ";
      } else {
        $sql.="like '%".$email."%' ";
      }
    }
    if($name != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`User`.`name` ";
      if($nameL == "exactly"){
        $sql.="= '".$name."' ";
      } else {
        $sql.="like '%".$name."%' ";
      }
    }
    if($surname != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`User`.`surname` ";
      if($surnameL == "exactly"){
        $sql.="= '".$surname."' ";
      } else {
        $sql.="like '%".$surname."%' ";
      }
    }
    if($shopName != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Artisan`.`shopName` ";
      if($shopNameL == "exactly"){
        $sql.="= '".$shopName."' ";
      } else {
        $sql.="like '%".$shopName."%' ";
      }
    }
    if($phoneNumber != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Artisan`.`phoneNumber` ";
      if($phoneNumberL == "exactly"){
        $sql.="= '".$phoneNumber."' ";
      } else {
        $sql.="like '%".$phoneNumber."%' ";
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
    $sql = $sqlInitialPart.$sql.$sqlFinalPart;
    //Show results
    $SearchPreviewArtisans = executeSql($sql);
    startCardGrid();
    $foundAtLeastOneResult = false;
    foreach($SearchPreviewArtisans as &$singleArtisanPreview){
      $foundAtLeastOneResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),translate("Total products of this artsan").": ".$singleArtisanPreview["numberOfProductsOfThisArtisan"]);
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
