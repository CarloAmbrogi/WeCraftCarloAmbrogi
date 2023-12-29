<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Visualize a project by id (the id is sent as a get)
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if(isset($_GET["id"])){
    if(doesThisProjectExists($_GET["id"])){
      addScriptAddThisPageToCronology();
      upperPartOfThePage(translate("Project"),"cookieBack");
      //Real content of this page
      //General info of this product
      $projectInfos = obtainProjectInfos($_GET["id"]);

    } else {
      upperPartOfThePage(translate("Error"),"");
      addParagraph(translate("This project doesnt exists"));
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("You have missed to specify the get param id of the project"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
