<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the analytics administrator
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Analytics administrator"),"");
    addScriptAddThisPageToCronology();
    //Content of this page

    //Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period
    addParagraph(translate("Artisans who have started to sell products of other artisan without having sold at least a certain quantity of their items in last period"));
    $minNumItems = 2;
    $durationLastPeriod = 5184000;
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
    addParagraph("Number of products per categories");
    addBarChart("numberOfProducts",translate("Number of products per categories"),[translate("All products"),translate("Nonee"),translate("Jewerly"),translate("Home decoration"),translate("Pottery"),translate("Teppiches"),translate("Bedware Bathroomware"),translate("Artisan craft")],[getNumberOfProducts(),getNumberOfProductsWithThisCategory("Nonee"),getNumberOfProductsWithThisCategory("Jewerly"),getNumberOfProductsWithThisCategory("Home decoration"),getNumberOfProductsWithThisCategory("Pottery"),getNumberOfProductsWithThisCategory("Teppiches"),getNumberOfProductsWithThisCategory("Bedware Bathroomware"),getNumberOfProductsWithThisCategory("Artisan craft")]);

    //New products in years
    addParagraph(translate("New products in years"));
    addBarChart("numberOfProductsYear",translate("New products in years"),["2023","2024","2025","2026"],[numberNewProductsYear(2023),numberNewProductsYear(2024),numberNewProductsYear(2025),numberNewProductsYear(2026)]);

    //Averange number of sells of a product
    addParagraph(translate("Averange number of sells of a product"));
    addBarChart("averangeNumberSells",translate("Averange number of sells of a product"),[translate("All products"),translate("Nonee"),translate("Jewerly"),translate("Home decoration"),translate("Pottery"),translate("Teppiches"),translate("Bedware Bathroomware"),translate("Artisan craft")],[averangeNumberSellsOfAProduct(),averangeNumberSellsOfAProductWithThisCategory("Nonee"),averangeNumberSellsOfAProductWithThisCategory("Jewerly"),averangeNumberSellsOfAProductWithThisCategory("Home decoration"),averangeNumberSellsOfAProductWithThisCategory("Pottery"),averangeNumberSellsOfAProductWithThisCategory("Teppiches"),averangeNumberSellsOfAProductWithThisCategory("Bedware Bathroomware"),averangeNumberSellsOfAProductWithThisCategory("Artisan craft")]);

    //Analytics related to collaboration
    addTitle(translate("Analytics related to collaboration"));

    //Number of products witch are sponsored by a certain number of artisans
    addParagraph(translate("Number of products witch are sponsored by a certain number of artisans"));
    addBarChart("numberOfProductsSponsoredByNumArtisans",translate("Number of products witch are sponsored by a certain number of artisans"),[translate("Nonee"),"1","2","3","4","5+"],[numberProductsNotSponsored(),numberProductsSponsoredByACertainNumberOfArtisans(1),numberProductsSponsoredByACertainNumberOfArtisans(2),numberProductsSponsoredByACertainNumberOfArtisans(3),numberProductsSponsoredByACertainNumberOfArtisans(4),numberProductsSponsoredByAtLeastACertainNumberOfArtisans(5)]);

    //Number of products witch are sold by a certain number of extra artisans
    addParagraph(translate("Number of products witch are sold by a certain number of extra artisans"));
    addBarChart("numberOfProductsSoldByNumArtisans",translate("Number of products witch are sold by a certain number of extra artisans"),[translate("Nonee"),"1","2","3","4","5+"],[numberProductsNotExchangeSold(),numberProductsSoldByACertainNumberOfArtisans(1),numberProductsSoldByACertainNumberOfArtisans(2),numberProductsSoldByACertainNumberOfArtisans(3),numberProductsSoldByACertainNumberOfArtisans(4),numberProductsSoldByAtLeastACertainNumberOfArtisans(5)]);

    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
