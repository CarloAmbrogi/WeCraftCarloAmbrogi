<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Search"),"");
  addButtonLink(translate("Log out"),"./logout.php");//AAAAAAAAAAAAA
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
