<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"");
    addScriptAddThisPageToCronology();
    //Content of this page

    //Title
    addTitle(translate("Analytical forecast"));

    //New users in years
    addParagraph(translate("New users in years"));
    $currentYear = date("Y");
    $percentageCurrentYear = (date("m") / 12.0) + ((date("d") / 31.0) / 12.0);
    $vv = [numberNewUsersYear(2023),numberNewUsersYear(2024),numberNewUsersYear(2025),numberNewUsersYear(2026)];
    $startYear = 2023;
    $endYear = 2026;
    $yearCounter = $startYear;
    $totalPrev = 0;
    $increment = 1.0;
    while($yearCounter <= $endYear){
      if($yearCounter < $currentYear){
        $percentageCurrentYear += 1.0;
        $totalPrev += $vv[$yearCounter-$startYear];
      }
      if($yearCounter == $currentYear){
        $totalPrev += $vv[$yearCounter-$startYear];
        $totalPrev = $totalPrev / $percentageCurrentYear;
        $vv[$yearCounter-$startYear] = $totalPrev;
        if(numberNewUsersYear($yearCounter-1) > 0.0){
          $increment = $totalPrev / numberNewUsersYear($yearCounter-1);
        }
      }
      if($yearCounter > $currentYear){
        $totalPrev = $totalPrev * $increment;
        $vv[$yearCounter-$startYear] = $totalPrev;
      }
      $yearCounter++;
    }
    addBarChart("numberOfUsersYear",translate("New users in years"),["2023","2024","2025","2026"],$vv);

    //New products in years
    addParagraph(translate("New products in years"));
    $currentYear = date("Y");
    $percentageCurrentYear = (date("m") / 12.0) + ((date("d") / 31.0) / 12.0);
    $vv = [numberNewProductsYear(2023),numberNewProductsYear(2024),numberNewProductsYear(2025),numberNewProductsYear(2026)];
    $startYear = 2023;
    $endYear = 2026;
    $yearCounter = $startYear;
    $totalPrev = 0;
    $increment = 1.0;
    while($yearCounter <= $endYear){
      if($yearCounter < $currentYear){
        $percentageCurrentYear += 1.0;
        $totalPrev += $vv[$yearCounter-$startYear];
      }
      if($yearCounter == $currentYear){
        $totalPrev += $vv[$yearCounter-$startYear];
        $totalPrev = $totalPrev / $percentageCurrentYear;
        $vv[$yearCounter-$startYear] = $totalPrev;
        if(numberNewProductsYear($yearCounter-1) > 0.0){
          $increment = $totalPrev / numberNewProductsYear($yearCounter-1);
        }
      }
      if($yearCounter > $currentYear){
        $totalPrev = $totalPrev * $increment;
        $vv[$yearCounter-$startYear] = $totalPrev;
      }
      $yearCounter++;
    }
    addBarChart("numberOfProductsYear",translate("New products in years"),["2023","2024","2025","2026"],$vv);

    //Collaboration production product score in years
    addParagraph(translate("Collaboration production product score in years")." (".translate("each time an action to add a person is performed it counts as one point").")");
    $currentYear = date("Y");
    $percentageCurrentYear = (date("m") / 12.0) + ((date("d") / 31.0) / 12.0);
    $vv = [collaborationProductionProductScoreYears(2023),collaborationProductionProductScoreYears(2024),collaborationProductionProductScoreYears(2025),collaborationProductionProductScoreYears(2026)];
    $startYear = 2023;
    $endYear = 2026;
    $yearCounter = $startYear;
    $totalPrev = 0;
    $increment = 1.0;
    while($yearCounter <= $endYear){
      if($yearCounter < $currentYear){
        $percentageCurrentYear += 1.0;
        $totalPrev += $vv[$yearCounter-$startYear];
      }
      if($yearCounter == $currentYear){
        $totalPrev += $vv[$yearCounter-$startYear];
        $totalPrev = $totalPrev / $percentageCurrentYear;
        $vv[$yearCounter-$startYear] = $totalPrev;
        if(collaborationProductionProductScoreYears($yearCounter-1) > 0.0){
          $increment = $totalPrev / collaborationProductionProductScoreYears($yearCounter-1);
        }
      }
      if($yearCounter > $currentYear){
        $totalPrev = $totalPrev * $increment;
        $vv[$yearCounter-$startYear] = $totalPrev;
      }
      $yearCounter++;
    }
    addBarChart("collaborationProductionProductScoreYears",translate("Score"),["2023","2024","2025","2026"],$vv);

    //Collaboration production project score in years
    addParagraph(translate("Collaboration production project score in years")." (".translate("each time an action to add a person is performed it counts as one point").")");
    $currentYear = date("Y");
    $percentageCurrentYear = (date("m") / 12.0) + ((date("d") / 31.0) / 12.0);
    $vv = [collaborationProductionProjectScoreYears(2023),collaborationProductionProjectScoreYears(2024),collaborationProductionProjectScoreYears(2025),collaborationProductionProjectScoreYears(2026)];
    $startYear = 2023;
    $endYear = 2026;
    $yearCounter = $startYear;
    $totalPrev = 0;
    $increment = 1.0;
    while($yearCounter <= $endYear){
      if($yearCounter < $currentYear){
        $percentageCurrentYear += 1.0;
        $totalPrev += $vv[$yearCounter-$startYear];
      }
      if($yearCounter == $currentYear){
        $totalPrev += $vv[$yearCounter-$startYear];
        $totalPrev = $totalPrev / $percentageCurrentYear;
        $vv[$yearCounter-$startYear] = $totalPrev;
        if(collaborationProductionProjectScoreYears($yearCounter-1) > 0.0){
          $increment = $totalPrev / collaborationProductionProjectScoreYears($yearCounter-1);
        }
      }
      if($yearCounter > $currentYear){
        $totalPrev = $totalPrev * $increment;
        $vv[$yearCounter-$startYear] = $totalPrev;
      }
      $yearCounter++;
    }
    addBarChart("collaborationProductionProjectScoreYears",translate("Score"),["2023","2024","2025","2026"],$vv);

    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
