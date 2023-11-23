<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Log out
  doInitialScripts();
  upperPartOfThePage(translate("Log out"),"");
  unset($_SESSION["userId"]);
  session_destroy();
  addParagraph(translate("Log out done"));
  addButtonLink(translate("Return to home"),"./index.php");
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
?>
