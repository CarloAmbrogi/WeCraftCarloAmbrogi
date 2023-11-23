<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse == "Designer" || $kindOfTheAccountInUse == "Artisan"){
    upperPartOfThePage(translate("Search"),"./more.php");
  } else {
    upperPartOfThePage(translate("Search"),"");
  }
  addButtonLink(translate("Log out"),"./logout.php");//AAAAAAAAAAAAA
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
