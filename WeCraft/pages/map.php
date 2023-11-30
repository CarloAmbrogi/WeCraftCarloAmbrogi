<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Map
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse == "Designer" || $kindOfTheAccountInUse == "Artisan"){
    upperPartOfThePage(translate("Map"),"./more.php");
  } else {
    upperPartOfThePage(translate("Map"),"");
  }
  addButtonLink(translate("Log out"),"./logout.php");//AAAAAAAAAAAAA
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>