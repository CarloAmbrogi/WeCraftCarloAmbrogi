<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Search
  doInitialScripts();
  upperPartOfThePage(translate("Search"),"");
  //Content of the page
  addTitle(translate("Search on WeCraft"));
  addParagraph(translate("Global search on WeCraft"));
  startFormGet("./gloabalSearch.php");
  addShortTextField(translate("Search"),"search",49);
  endFormGet(translate("SubmitSearch"));
  addTitle(translate("Advanced search"));
  addParagraph(translate("What are you searching")."?");
  addButtonLink(translate("Search product"),"./searchProduct.php");
  addButtonLink(translate("Search artisan"),"./searchArtisan.php");
  addButtonLink(translate("Search designer"),"./searchDesigner.php");

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
