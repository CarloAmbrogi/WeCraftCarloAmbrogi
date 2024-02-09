<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  //Hystorical analytics
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"");
    addScriptAddThisPageToCronology();
    //Content of this page

    //default start date and end date
    $startDate = "2020-12-13 10:40:17";
    $endDate = "2055-12-13 10:40:17";

    //set params via get
    if(isset($_GET["startDate"]) && isset($_GET["endDate"])){
      $startDate = $_GET["startDate"];
      $endDate = $_GET["endDate"];
    }

    //Form to set start date and end date
    startFormGet("./historicalAnalytics.php");
    addParagraph(translate("Use the format")." "."yyyy-mm-dd hh-mm-ss");
    addShortTextField(translate("Start date"),"startDate",49);
    addShortTextField(translate("End date"),"endDate",49);
    endFormGet(translate("Submit"));
    addButtonLink(translate("Reset default"),"./historicalAnalytics.php");
    //Load previous inserted values in the form
    ?>
    <script>
      //form inserted parameters
      const form = document.querySelector('form');
      const startDate = document.getElementById('startDate');
      const endDate = document.getElementById('endDate');

      //Load form fields starting values
      startDate.value = "<?= $startDate ?>";
      endDate.value = "<?= $endDate ?>";
    </script>
  <?php

    //Inserted start / end date
    addTitle(translate("Historical analytics"));
    addParagraph(translate("Start date").": ".$startDate);
    addParagraph(translate("End date").": ".$endDate);

    //General statistics of WeCraft
    addTitle(translate("General statistics of WeCraft"));

    //Number of users divided by categories historical
    addParagraph(translate("Number of users registered from")." ".$startDate." ".translate("toa")." ".$endDate);
    addBarChart("numberOfUsers",translate("Number of users"),[translate("All users"),translate("Customer"),translate("Artisan"),translate("Designer")],[getNumberOfUsersHistorical($startDate,$endDate),getNumberOfCustomersHistorical($startDate,$endDate),getNumberOfArtisansHistorical($startDate,$endDate),getNumberOfDesignersHistorical($startDate,$endDate)]);
    
    //Number of products per categories historical
    addParagraph(translate("Number of products per categories")." (".translate("products added from")." ".$startDate." ".translate("toa")." ".$endDate.")");
    addBarChart("numberOfProducts",translate("Number of products per categories"),[translate("All products"),translate("Nonee"),translate("Jewerly"),translate("Home decoration"),translate("Pottery"),translate("Teppiches"),translate("Bedware Bathroomware"),translate("Artisan craft")],[getNumberOfProductsHistorical($startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Nonee",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Jewerly",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Home decoration",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Pottery",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Teppiches",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Bedware Bathroomware",$startDate,$endDate),getNumberOfProductsWithThisCategoryHistorical("Artisan craft",$startDate,$endDate)]);

    //Analytics related to collaboration
    addTitle(translate("Analytics related to collaboration"));

    //Number of products in cooperation for the production historical
    addParagraph(translate("Number of products added from")." ".$startDate." ".translate("toa")." ".$endDate." ".translate("which are now in cooperation for the production or not"));
    addBarChart("numProductsCooperationProduction",translate("Number of products in cooperation for the production or not"),[translate("Yesx"),translate("Nox")],[numberProductsInCooperationForTheProductionHistorical($startDate,$endDate),numberProductsNotInCooperationForTheProductionHistorical($startDate,$endDate)]);

    //Number of products added from X that in date X have a certain number of collaborators
    addParagraph(translate("Number of products added from")." ".$startDate." ".translate("that in date")." ".$endDate." ".translate("have a certain number of collaborators"));
    $maxProductId = maxProductId();
    $productArray = [];
    $countArrayCreator = 0;
    while($countArrayCreator <= $maxProductId){
      if(isThisProductAddedBetweenDates($countArrayCreator,$startDate,$endDate)){
        array_push($productArray,0);
      } else {
        array_push($productArray,-1);
      }
      $countArrayCreator++;
    }
    $cooperativeProductionProductsTrig = obtainCooperativeProductionProductsTrigLimitDate($startDate,$endDate);
    foreach($cooperativeProductionProductsTrig as &$singleTrig){
      if($productArray[$singleTrig["product"]] != -1){
        if($singleTrig["action"] == "insert"){
          $productArray[$singleTrig["product"]]++;
        }
        if($singleTrig["action"] == "delete"){
          $productArray[$singleTrig["product"]]--;
        }
      }
    }
    $cont0 = 0;
    $cont1 = 0;
    $cont2 = 0;
    $cont3 = 0;
    $cont4 = 0;
    $cont5p = 0;
    foreach($productArray as &$singleProd){
      if($singleProd == 0){
        $cont0++;
      }
      if($singleProd == 1){
        $cont1++;
      }
      if($singleProd == 2){
        $cont2++;
      }
      if($singleProd == 3){
        $cont3++;
      }
      if($singleProd == 4){
        $cont4++;
      }
      if($singleProd >= 5){
        $cont5p++;
      }
    }
    addBarChart("numberProductsWithNumCollaborators",translate("Number products with a certain number of collaborators"),["0","1","2","3","4","5+"],[$cont0,$cont1,$cont2,$cont3,$cont4,$cont5p]);

    //Products added from X to X which have never been in collaboration, have been in collaboration but never with a designer, have been in collaboration with a designer
    addParagraph(translate("Products added from")." ".$startDate." ".translate("toa")." ".$endDate." ".translate("which have never been in collaboration or have been in collaboration but never with a designer or have been in collaboration with a designer"));
    addBarChart("numberCooperationsProductsWithDesigner",translate("Products collaborations"),[translate("Never in collaboration"),translate("Never designer"),translate("Designer")],[numberProductsAddedBetweenDatesNeverBeenCollaboration($startDate,$endDate),numberProductsAddedBetweenDatesBeenCollaborationNeverDesinger($startDate,$endDate),numberProductsAddedBetweenDatesBeenCollaborationDesinger($startDate,$endDate)]);
    
    //Analytics related to projects for personalized items
    addTitle(translate("Analytics related to projects for personalized items"));

    //Number of completed projects that have been completed in time or not historical (projects confirmed between X and X)
    addParagraph(translate("Number of completed projects that have been completed in time or not")." (".translate("projects confirmed between")." ".$startDate." ".translate("and")." ".$endDate.")");
    addBarChart("numProjectsComplInTime",translate("Number of projects that have been completed in time"),[translate("Completed in time"),translate("Completed but not in time"),translate("Not completed and in delay")],[numberOfProjectsCompletedInTimeHistorical($startDate,$endDate),numberOfProjectsNotCompletedInTimeHistorical($startDate,$endDate),numberOfProjectsNotCompletedInDelayHistorical($startDate,$endDate)]);

    //Duration of projects historical (projects confirmed between X and X)
    addParagraph(translate("Duration of projects")." (".translate("projects confirmed between")." ".$startDate." ".translate("and")." ".$endDate.")");
    addBarChart("durationOfProjects",translate("Duration of projects"),[translate("Within a month"),translate("Within two months"),translate("More than two months")],[numberCompletedProjectsInCertainTimeRangeHistorical(0,2592000,$startDate,$endDate),numberCompletedProjectsInCertainTimeRangeHistorical(2592000,5184000,$startDate,$endDate),numberCompletedProjectsInAtLeastCertainTimeRangeHistorical(5184000,$startDate,$endDate)]);

    //Number of projects in cooperation for the production which have been confirmed between X and X (also completed project)
    addParagraph(translate("Number of projects in cooperation for the production")." (".translate("projects confirmed between")." ".$startDate." ".translate("and")." ".$endDate.")");
    addBarChart("numProjectsCooperationProduction",translate("Number of projects in cooperation for the production or not"),[translate("Yesx"),translate("Nox")],[numberProjectsInCooperationForTheProductionConfirmedBetweenDates($startDate,$endDate),numberProjectsNotInCooperationForTheProductionConfirmedBetweenDates($startDate,$endDate)]);

    //Number of projects confirmed from X that in date X have a certain number of collaborators
    addParagraph(translate("Number of projects confirmed from")." ".$startDate." ".translate("that in date")." ".$endDate." ".translate("have a certain number of collaborators"));
    $maxProjectId = maxProjectId();
    $projectArray = [];
    $countArrayCreator = 0;
    while($countArrayCreator <= $maxProjectId){
      if(isThisProductAddedBetweenDates($countArrayCreator,$startDate,$endDate)){
        array_push($projectArray,0);
      } else {
        array_push($projectArray,-1);
      }
      $countArrayCreator++;
    }
    $cooperativeProductionProjectsTrig = obtainCooperativeProductionProjectsTrigLimitDate($startDate,$endDate);
    foreach($cooperativeProductionProjectsTrig as &$singleTrig){
      if($projectArray[$singleTrig["project"]] != -1){
        if($singleTrig["action"] == "insert"){
          $projectArray[$singleTrig["project"]]++;
        }
        if($singleTrig["action"] == "delete"){
          $projectArray[$singleTrig["project"]]--;
        }
      }
    }
    $cont0 = 0;
    $cont1 = 0;
    $cont2 = 0;
    $cont3 = 0;
    $cont4 = 0;
    $cont5p = 0;
    foreach($projectArray as &$singleProd){
      if($singleProd == 0){
        $cont0++;
      }
      if($singleProd == 1){
        $cont1++;
      }
      if($singleProd == 2){
        $cont2++;
      }
      if($singleProd == 3){
        $cont3++;
      }
      if($singleProd == 4){
        $cont4++;
      }
      if($singleProd >= 5){
        $cont5p++;
      }
    }
    addBarChart("numberProjectsWithNumCollaborators",translate("Number projects with a certain number of collaborators"),["0","1","2","3","4","5+"],[$cont0,$cont1,$cont2,$cont3,$cont4,$cont5p]);

    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
