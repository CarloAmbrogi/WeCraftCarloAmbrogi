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
    addScriptAddThisPageToChronology();
    //Content of this page

    addTitle(translate("Advanced query users"));
    
    //Email verified
    //Active
    //Time registration
    //Kind of user

    //Form
    startFormGet("./advancedQueryUsers.php");
    addParagraph(translate("The fields you compile will be considered"));
    addSelectorWithLabel(translate("Email verified"),"emailVerified",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addSelectorWithLabel(translate("Active"),"active",["","y","n"],[translate("Dont specify"),translate("Yesx"),translate("Nox")]);
    addShortTextField(translate("Time registration from")." ".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss","timeRegistrationFrom",49);
    addShortTextField(translate("Time registration to")." ".translate("Use the format")." "."yyyy-mm-dd hh:mm:ss","timeRegistrationTo",49);
    addSelectorWithLabel(translate("Kind of user"),"kindOfUser",["","Customer","Artisan","Designer"],[translate("Any"),translate("Customer"),translate("Artisan"),translate("Designer")]);
    endFormGet(translate("Submit"));
    addButtonLink(translate("Clean research"),"./advancedQueryUsers.php");
    //Load previous inserted values in the form
    ?>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const emailVerified = document.getElementById('emailVerified');
        const active = document.getElementById('active');
        const timeRegistrationFrom = document.getElementById('timeRegistrationFrom');
        const timeRegistrationTo = document.getElementById('timeRegistrationTo');
        const kindOfUser = document.getElementById('kindOfUser');

        //Load form fields starting values
        emailVerified.value = "<?= $_GET["emailVerified"] ?>";
        active.value = "<?= $_GET["active"] ?>";
        timeRegistrationFrom.value = "<?= $_GET["timeRegistrationFrom"] ?>";
        timeRegistrationTo.value = "<?= $_GET["timeRegistrationTo"] ?>";
        kindOfUser.value = "<?= $_GET["kindOfUser"] ?>";
      </script>
    <?php
    //sql
    $sqlInitial = "select `User`.`id`,`User`.`name`,`User`.`surname`,t.kindOfThisAccount as kindOfThisUser,`icon`,`iconExtension` from `User` join ((SELECT `id`,'Designer' as 'kindOfThisAccount' FROM `Designer`) UNION (SELECT `id`,'Artisan' as 'kindOfThisAccount' FROM `Artisan`) UNION (SELECT `id`,'Customer' as 'kindOfThisAccount' FROM `Customer`)) as t on `User`.`id` = t.`id` where 1 ";
    $sqlMid = "";
    $sqlFinal = " order by `User`.`id` DESC;";
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
    if($_GET["kindOfUser"] != ""){
      $sqlMid.=" and `User`.`id` in (select `id` from `".$_GET["kindOfUser"]."`) ";
    }
    $sql = $sqlInitial.$sqlMid.$sqlFinal;
    //Show results
    $SearchPreviewUsers = executeSqlDoNotShowTheError($sql);
    $numberResults = 0;
    startCardGrid();
    foreach($SearchPreviewUsers as &$singleUserPreview){
      $fileImageToVisualize = genericUserImage;
      if(isset($singleUserPreview['icon']) && ($singleUserPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleUserPreview["iconExtension"],$singleUserPreview['icon']);
      }
      $title = $singleUserPreview['name']." ".$singleUserPreview['surname'];
      $text1 = $singleUserPreview['kindOfThisUser'];
      $text2 = "";
      if($singleUserPreview['kindOfThisUser'] == "Customer"){
        addACard("./chat.php?chatKind=".urlencode("personal")."&chatWith=".urlencode($singleUserPreview["id"]),$fileImageToVisualize,htmlentities($title),$text1,$text2);
      }
      if($singleUserPreview['kindOfThisUser'] == "Artisan"){
        addACardForTheGrid("./artisan.php?id=".urlencode($singleUserPreview["id"]),$fileImageToVisualize,htmlentities($title),$text1,$text2);
      }
      if($singleUserPreview['kindOfThisUser'] == "Designer"){
        addACardForTheGrid("./designer.php?id=".urlencode($singleUserPreview["id"]),$fileImageToVisualize,htmlentities($title),$text1,$text2);
      }
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
