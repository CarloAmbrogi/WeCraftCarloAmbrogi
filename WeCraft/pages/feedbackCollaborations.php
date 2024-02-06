<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page reserved for the admin
  doInitialScripts();
  if($_SESSION["userId"] == "admin"){
    upperPartOfThePage(translate("Admin"),"");
    addScriptAddThisPageToCronology();
    //Content of this page
    addTitle(translate("Feedback collaborations"));
    $feedbacks = obtainFeedbacksPreview();
    $foundFeedback = false;
    foreach($feedbacks as &$feedback){
      $foundFeedback = true;
      startSquare();
      addParagraph(translate("From")." ".$feedback["name"]." ".$feedback["surname"]." (".$feedback["fromKind"].")"." [".$feedback["timestamp"]."]");
      if($feedback["fromKind"] == "Artisan" || $feedback["fromKind"] == "owner" || $feedback["fromKind"] == "claimer"){
        addButtonLink(translate("See artisan"),"./artisan.php?id=".urlencode($feedback["fromWho"]));
      }
      if($feedback["fromKind"] == "Designer"){
        addButtonLink(translate("See designer"),"./designer.php?id=".urlencode($feedback["fromWho"]));
      }
      if($feedback["toWhatKind"] == "product"){
        addButtonLink(translate("See related product"),"./product.php?id=".urlencode($feedback["toWhat"]));
      }
      if($feedback["toWhatKind"] == "project"){
        addButtonLink(translate("See related project"),"./project.php?id=".urlencode($feedback["toWhat"]));
      }
      addParagraph($feedback["feedback"]);
      endSquare();
    }
    if($foundFeedback == false){
      addParagraph(translate("No feedback for now"));
    }
    //End of this page
  } else {
    upperPartOfThePage(translate("Error"),"");
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
