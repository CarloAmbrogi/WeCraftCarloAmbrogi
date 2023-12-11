<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search Designer
  doInitialScripts();
  upperPartOfThePage(translate("Search"),"./search.php");
  //Content of the page
  addTitle(translate("Search designer"));
  startFormGet("./searchDesigner.php");
  addParagraph(translate("The fields you compile will be considered"));
  addShortTextFieldWithLike(translate("Email address"),"email","emailL",49);
  addShortTextFieldWithLike(translate("Name"),"name","nameL",49);
  addShortTextFieldWithLike(translate("Surname"),"surname","surnameL",49);
  addShortTextFieldWithLike(translate("Description"),"description","descriptionL",49);
  addSelector2Options(translate("All theese conditions"),translate("At least one of theese conditions"),"and","or","cond");
  endFormGet(translate("SubmitSearch"));
  addButtonLink(translate("Clean research"),"./searchDesigner.php");
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
      description.value = "<?= $description ?>";
      descriptionL.value = "<?= $descriptionL ?>";
      cond.value = "<?= $cond ?>";
    </script>
  <?php
  //Research results
  if($email != "" || $name != "" || $surname != "" || $description != ""){
    addSubtopicIndex("researchResults");
    addTitle(translate("Research results").":");
    ?>
      <script>
        window.location = "#researchResults";
      </script>
    <?php
    //Prepare sql
    $sqlInitialPart = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension` from `User` join `Designer` on `User`.`id` = `Designer`.`id` where ";
    $sqlMidExample = "`User`.`email` like ? or `User`.`name` like ? or `User`.`surname` like ? or concat(`User`.`name`,' ',`User`.`surname`) like ? or concat(`User`.`surname`,' ',`User`.`name`) like ? or `Designer`.`description` like ? ";
    $sqlFinalPart = "order by `User`.`id` limit 100;";
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
    if($description != ""){
      if($sql != ""){
        $sql.=$cond." ";
      }
      $sql.="`Designer`.`description` ";
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
    foreach($SearchPreviewArtisans as &$singleDesignerPreview){
      $foundAtLeastOneResult = true;
      $fileImageToVisualize = genericUserImage;
      if(isset($singleDesignerPreview['icon']) && ($singleDesignerPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleDesignerPreview["iconExtension"],$singleDesignerPreview['icon']);
      }
      addACardForTheGrid("./designer.php?id=".$singleDesignerPreview["id"],$fileImageToVisualize,htmlentities($singleDesignerPreview["name"]." ".$singleDesignerPreview["surname"]),translate("Designer"),"");
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
