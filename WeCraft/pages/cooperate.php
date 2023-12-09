<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperate page
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperate"),"");
    addTitle(translate("Three ways to cooperate")."...");
    startSquare();
    addButtonLink(translate("Cooperative design"),"./AAAAAAAAAAAAA");
    addParagraph(translate("More artisans cooperate together to realize the same artifact"));
    endSquare();
    startSquare();
    addButtonLink(translate("Cooperation for visibility and marketing"),"./cooperationVisibilityAndMarketing.php");
    addParagraph(translate("The artisans agree to sponsor each others products"));
    endSquare();
    startSquare();
    addButtonLink(translate("Exchange complementary products"),"./AAAAAAAAAAAAA");
    addParagraph(translate("Artisans sell other artisans products at their physical stores and WeCrafts system helps by suggesting possible artisans who create complementary products"));
    endSquare();
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
