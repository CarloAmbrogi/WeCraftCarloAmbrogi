<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperate page
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan" && $kindOfTheAccountInUse != "Designer"){
    //This page is visible only for artisans or designers
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    if($kindOfTheAccountInUse == "Artisan"){
      upperPartOfThePage(translate("Cooperate"),"");
      addTitle(translate("Different ways to cooperate")."...");
      startSquare();
      addButtonLink(translate("Cooperative design"),"./cooperativeDesignForProducts.php");
      addParagraph(translate("More artisans and designers cooperate together to realize the same artifact"));
      endSquare();
      startSquare();
      addButtonLink(translate("Cooperation for visibility and marketing"),"./cooperationVisibilityAndMarketing.php");
      addParagraph(translate("The artisans agree to sponsor each others products"));
      endSquare();
      startSquare();
      addButtonLink(translate("Exchange complementary products"),"./exchangeComplementaryProducts.php");
      addParagraph(translate("Artisans sell other artisans products at their stores and WeCrafts system helps by suggesting possible artisans who create complementary products"));
      endSquare();
      startSquare();
      addButtonLink(translate("Cooperative design for personalized items"),"./cooperativeDesignForProjects.php");
      addParagraph(translate("More artisans and designers cooperate together to realize a personalized item and if you want you can collaborate with the designer who has created the project and with the artisans that the designer has selected"));
      endSquare();
    }
    if($kindOfTheAccountInUse == "Designer"){
      upperPartOfThePage(translate("Cooperate"),"");
      addParagraph(translate("Designers can cooperate with artisans to help them realizing products"));
      startSquare();
      addButtonLink(translate("Cooperative design"),"./cooperativeDesignForProducts.php");
      addParagraph(translate("You can collaborate together with other artisans to realize artifacts"));
      endSquare();
      startSquare();
      addButtonLink(translate("Cooperative design for personalized items"),"./cooperativeDesignForProjects.php");
      addParagraph(translate("More artisans and designers cooperate together to realize a personalized item"));
      endSquare();
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
