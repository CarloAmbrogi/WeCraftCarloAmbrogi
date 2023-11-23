<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Map
  doInitialScripts();
  upperPartOfThePage(translate("Map"),"");
  addButtonLink(translate("Log out"),"./logout.php");//AAAAAAAAAAAAA
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
?>
