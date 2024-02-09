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

    //Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period
    addParagraph(translate("Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period"));
    $minNumItems = 2;
    $durationLastPeriod = 5184000;//60 days
    $previewArtisansWhoAreOnlyResellingItems = obtainPreviewArtisansWhoArePraticallyOnlyResellingItems($minNumItems,$durationLastPeriod);
    startCardGrid();
    foreach($previewArtisansWhoAreOnlyResellingItems as &$singleArtisanPreview){
      $fileImageToVisualize = genericUserImage;
      if(isset($singleArtisanPreview['icon']) && ($singleArtisanPreview['icon'] != null)){
        $fileImageToVisualize = blobToFile($singleArtisanPreview["iconExtension"],$singleArtisanPreview['icon']);
      }
      addACardForTheGrid("./artisan.php?id=".urlencode($singleArtisanPreview["id"]),$fileImageToVisualize,htmlentities($singleArtisanPreview["name"]." ".$singleArtisanPreview["surname"]),htmlentities($singleArtisanPreview["shopName"]),"");
    }
    endCardGrid();

    //General statistics of WeCraft
    addTitle(translate("General statistics of WeCraft"));

    //Number of users divided by categories
    addParagraph(translate("Number of users"));
    addBarChart("numberOfUsers",translate("Number of users"),[translate("All users"),translate("Customer"),translate("Artisan"),translate("Designer")],[getNumberOfUsers(),getNumberOfCustomers(),getNumberOfArtisans(),getNumberOfDesigners()]);
    
    //New users in years
    addParagraph(translate("New users in years"));
    addBarChart("numberOfUsersYear",translate("New users in years"),["2023","2024","2025","2026"],[numberNewUsersYear(2023),numberNewUsersYear(2024),numberNewUsersYear(2025),numberNewUsersYear(2026)]);

    //Number of products per categories
    addParagraph(translate("Number of products per categories"));
    addBarChart("numberOfProducts",translate("Number of products per categories"),[translate("All products"),translate("Nonee"),translate("Jewerly"),translate("Home decoration"),translate("Pottery"),translate("Teppiches"),translate("Bedware Bathroomware"),translate("Artisan craft")],[getNumberOfProducts(),getNumberOfProductsWithThisCategory("Nonee"),getNumberOfProductsWithThisCategory("Jewerly"),getNumberOfProductsWithThisCategory("Home decoration"),getNumberOfProductsWithThisCategory("Pottery"),getNumberOfProductsWithThisCategory("Teppiches"),getNumberOfProductsWithThisCategory("Bedware Bathroomware"),getNumberOfProductsWithThisCategory("Artisan craft")]);

    //New products in years
    addParagraph(translate("New products in years"));
    addBarChart("numberOfProductsYear",translate("New products in years"),["2023","2024","2025","2026"],[numberNewProductsYear(2023),numberNewProductsYear(2024),numberNewProductsYear(2025),numberNewProductsYear(2026)]);

    //Averange number of sells of a product
    addParagraph(translate("Averange number of sells of a product"));
    addBarChart("averangeNumberSells",translate("Averange number of sells of a product"),[translate("All products"),translate("Nonee"),translate("Jewerly"),translate("Home decoration"),translate("Pottery"),translate("Teppiches"),translate("Bedware Bathroomware"),translate("Artisan craft")],[averangeNumberSellsOfAProduct(),averangeNumberSellsOfAProductWithThisCategory("Nonee"),averangeNumberSellsOfAProductWithThisCategory("Jewerly"),averangeNumberSellsOfAProductWithThisCategory("Home decoration"),averangeNumberSellsOfAProductWithThisCategory("Pottery"),averangeNumberSellsOfAProductWithThisCategory("Teppiches"),averangeNumberSellsOfAProductWithThisCategory("Bedware Bathroomware"),averangeNumberSellsOfAProductWithThisCategory("Artisan craft")],false);

    //Number of products sold for at least a certain number of units
    addParagraph(translate("Number of products sold for at least a certain number of units"));
    addBarChart("numberProductsSoldAtLeastQuantity",translate("Number of products sold for at least a certain number of units"),[0,1,5,10,15,25],[numberProductsSoldAtLeastNUnits(0),numberProductsSoldAtLeastNUnits(1),numberProductsSoldAtLeastNUnits(5),numberProductsSoldAtLeastNUnits(10),numberProductsSoldAtLeastNUnits(15),numberProductsSoldAtLeastNUnits(25)]);

    //Analytics related to collaboration
    addTitle(translate("Analytics related to collaboration"));

    //Number of products witch are sponsored by a certain number of artisans
    addParagraph(translate("Number of products witch are sponsored by a certain number of artisans"));
    addBarChart("numberOfProductsSponsoredByNumArtisans",translate("Number of products witch are sponsored by a certain number of artisans"),[translate("Nonee"),"1","2","3","4","5+"],[numberProductsNotSponsored(),numberProductsSponsoredByACertainNumberOfArtisans(1),numberProductsSponsoredByACertainNumberOfArtisans(2),numberProductsSponsoredByACertainNumberOfArtisans(3),numberProductsSponsoredByACertainNumberOfArtisans(4),numberProductsSponsoredByAtLeastACertainNumberOfArtisans(5)]);

    //Number of products witch are sold by a certain number of extra artisans
    addParagraph(translate("Number of products witch are sold by a certain number of extra artisans"));
    addBarChart("numberOfProductsSoldByNumArtisans",translate("Number of products witch are sold by a certain number of extra artisans"),[translate("Nonee"),"1","2","3","4","5+"],[numberProductsNotExchangeSold(),numberProductsSoldByACertainNumberOfArtisans(1),numberProductsSoldByACertainNumberOfArtisans(2),numberProductsSoldByACertainNumberOfArtisans(3),numberProductsSoldByACertainNumberOfArtisans(4),numberProductsSoldByAtLeastACertainNumberOfArtisans(5)]);

    //Number of products in cooperation for the production
    addParagraph(translate("Number of products in cooperation for the production"));
    addBarChart("numProductsCooperationProduction",translate("Number of products in cooperation for the production or not"),[translate("Yesx"),translate("Nox")],[numberProductsInCooperationForTheProduction(),numberProductsNotInCooperationForTheProduction()]);

    //Number of cooperations for products with a certain number of collaborators
    addParagraph(translate("Number of cooperations for products with a certain number of collaborators"));
    addBarChart("numberCooperationsProductsWithNumCollaborators",translate("Number of cooperations for products with a certain number of collaborators"),["1","2","3","4","5+"],[numberCooperationsForProductsWithACertainNumberOfCollaborations(1),numberCooperationsForProductsWithACertainNumberOfCollaborations(2),numberCooperationsForProductsWithACertainNumberOfCollaborations(3),numberCooperationsForProductsWithACertainNumberOfCollaborations(4),numberCooperationsForProductsWithAtLeastACertainNumberOfCollaborations(5)]);

    //Cooperations for the production of a product with at least a designer
    addParagraph(translate("Cooperations for the production of a product with at least a designer"));
    addBarChart("numberCooperationsProductsWithDesigner",translate("Cooperations for the production of a product with at least a designer or not"),[translate("Yesx"),translate("Nox")],[numberCooperationsProductWithADesigner(),numberCooperationsProductWithoutADesigner()]);

    //Averange number of product for which an artisan or a designer is collaborating for the production
    addParagraph(translate("Averange number of product for which an artisan or a designer is collaborating for the production"));
    addBarChart("averangeNumCollabProdForUsers",translate("Averange number of product for which an artisan or a designer is collaborating for the production"),[translate("Artisans"),translate("Designers"),translate("Artisans and designers")],[averangeNumberProductsForWhichArtisanCollaborating(),averangeNumberProductsForWhichDesignerCollaborating(),averangeNumberProductsForWhichArtisanDesignerCollaborating()],false);

    //Collaboration production product score in years
    addParagraph(translate("Collaboration production product score in years")." (".translate("each time an action to add a person is performed it counts as one point").")");
    addBarChart("collaborationProductionProductScoreYears",translate("Score"),["2023","2024","2025","2026"],[collaborationProductionProductScoreYears(2023),collaborationProductionProductScoreYears(2024),collaborationProductionProductScoreYears(2025),collaborationProductionProductScoreYears(2026)]);

    //Analytics related to projects for personalized items
    addTitle(translate("Analytics related to projects for personalized items"));

    //Number of projects public or private
    addParagraph(translate("Number of projects public or private"));
    addBarChart("numProjectsPubPriv",translate("Number of projects public or private"),[translate("Public"),translate("Private")],[numberOfProjectsPublic(),numberOfProjectsPrivate()]);

    //Number of completed projects that have been completed in time or not
    addParagraph(translate("Number of completed projects that have been completed in time or not"));
    addBarChart("numProjectsComplInTime",translate("Number of projects that have been completed in time"),[translate("Completed in time"),translate("Completed but not in time"),translate("Not completed and in delay")],[numberOfProjectsCompletedInTime(),numberOfProjectsNotCompletedInTime(),numberOfProjectsNotCompletedInDelay()]);

    //Number of projects that have been completed within a certain time range
    addParagraph(translate("Number of projects that have been completed within a certain time range"));
    addBarChart("numCompletedProjectsInCertainTimeRange",translate("Number of projects that have been completed within a certain time range"),[translate("Within a day"),translate("Within a week and at least one day"),translate("More than one week")],[numberCompletedProjectsInCertainTimeRange(0,86400),numberCompletedProjectsInCertainTimeRange(86400,604800),numberCompletedProjectsInAtLeastCertainTimeRange(604800)]);

    //Number of projects per state
    addParagraph(translate("Number of projects per state"));
    addBarChart("numProjectsPerState",translate("Number of projects per state"),[translate("Not presented to any artisan"),translate("Presented but not claimed"),translate("Claimed but not confirmed"),translate("Confirmed not completed"),translate("Completed")],[numberProjectsNotAssigned(),numberProjectsAssignedNotClaimed(),numberProjectsClaimedNotConfirmed(),numberProjectsConfirmedNotCompleted(),numberProjectsCompleted()]);

    //Number of projects in cooperation for the production
    addParagraph(translate("Number of projects in cooperation for the production"));
    addBarChart("numProjectsCooperationProduction",translate("Number of projects in cooperation for the production or not"),[translate("Yesx"),translate("Nox")],[numberProjectsInCooperationForTheProduction(),numberProjectsNotInCooperationForTheProduction()]);

    //Number of cooperations for projects with a certain number of collaborators
    addParagraph(translate("Number of cooperations for projects with a certain number of collaborators"));
    addBarChart("numberCooperationsProjectsWithNumCollaborators",translate("Number of cooperations for projects with a certain number of collaborators"),["1","2","3","4","5+"],[numberCooperationsForProjectsWithACertainNumberOfCollaborations(1),numberCooperationsForProjectsWithACertainNumberOfCollaborations(2),numberCooperationsForProjectsWithACertainNumberOfCollaborations(3),numberCooperationsForProjectsWithACertainNumberOfCollaborations(4),numberCooperationsForProjectsWithAtLeastACertainNumberOfCollaborations(5)]);

    //Averange number of projects for which an artisan or a designer is collaborating for the production
    addParagraph(translate("Averange number of projects for which an artisan or a designer is collaborating for the production"));
    addBarChart("averangeNumCollabProjForUsers",translate("Averange number of projects for which an artisan or a designer is collaborating for the production"),[translate("Artisans"),translate("Designers"),translate("Artisans and designers")],[averangeNumberProjectsForWhichArtisanCollaborating(),averangeNumberProjectsForWhichDesignerCollaborating(),averangeNumberProjectsForWhichArtisanDesignerCollaborating()],false);

    //Collaboration production project score in years
    addParagraph(translate("Collaboration production project score in years")." (".translate("each time an action to add a person is performed it counts as one point").")");
    addBarChart("collaborationProductionProjectScoreYears",translate("Score"),["2023","2024","2025","2026"],[collaborationProductionProjectScoreYears(2023),collaborationProductionProjectScoreYears(2024),collaborationProductionProjectScoreYears(2025),collaborationProductionProjectScoreYears(2026)]);

    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
