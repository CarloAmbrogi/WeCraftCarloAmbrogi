<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //More
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("More"),"");
  addButtonLink(translate("Log out"),"./logout.php");//AAAAAAAAAAAAA
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
