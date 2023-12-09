<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Cooperation for visibility and marketing
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only for artisans
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Cooperate"),"./cooperate.php");
    addTitle(translate("Cooperation for visibility and marketing"));
    addParagraph(translate("Here will be shown the products of other artisans you are sponsoring on your artisan page"));
    //Show the products you are sponsoring

    addParagraph(translate("If you find an interesting product you want to sponsor on your artisan page, you can add it from the product page"));
    addButtonLink(translate("Remove products"),"./AAAAAAAAAA");
    addTitle(translate("Suggested products"));
    addParagraph(translate("Here there are some suggested products you could sponsor on your artisan page")."...");
    addParagraph(translate("Products of artisans who are sponsoring some of your products").":");
    //Show some products of artisans who are sponsoring your products

    addParagraph(translate("Products of artisans whoose you are sponsoring some other products").":");
    //Show some products of artisans whoose you are sponsoring some other products

  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
