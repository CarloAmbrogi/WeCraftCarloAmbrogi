<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  //Advanced query
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"");
    addScriptAddThisPageToCronology();
    //Content of this page

    addTitle(translate("Advanced query"));

    //What is possible to select with this query

    //Users
    //Products
    //Projects


    //Users

    //Email verified
    //Active
    //Time registration
    //Kind of user

    //In case of artisan
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


    //Products

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


    //Projects

    //Added by an active designer
    //Price
    //Percentage to designer
    //State
    //Public or private
    //Timestamp purchase
    //Timestamp ready
    //If it is now in cooperation for the production
    //If it is now in cooperation for the production with at least a designer in the group
    //If it is now in cooperation for the production with a certain number of participants in the group
    //If it has been in cooperation for the production
    //Estimated duration
    //Completed and in time


    addButtonLink(translate("Users"),"./advancedQueryUsers.php");
    addButtonLink(translate("Artisans"),"./advancedQueryArtisans.php");
    addButtonLink(translate("Products"),"./advancedQueryProducts.php");
    addButtonLink(translate("Projects"),"./advancedQueryProjects.php");


    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
