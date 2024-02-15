<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page to write a a feedback about how has gone the collaboration
  //You can write a feedback using this page only if you have received a message in your chat
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request for sending a feedback
      $insertedFeedback = $_POST['insertedFeedback'];
      $insertedWhat = $_POST['insertedWhat'];//id of the product or of the project
      $insertedWhatKind = $_POST['insertedWhatKind'];//product or project
      upperPartOfThePage(translate("Send feedback collaboration"),"cookieBack");
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedFeedback == ""){
        addParagraph(translate("You have missed to insert the feedback"));
      } else if(strlen($insertedFeedback) > 2046){
        addParagraph(translate("The feedback is too long"));
      } else if($insertedWhatKind != "product" && $insertedWhatKind != "project"){
        addParagraph(translate("Error in the kind of collaboration"));
      } else {
        //Check existence product or project
        $checkExistenceProductOrProjectOk = false;
        if($insertedWhatKind == "product"){
          if(doesThisProductExists($insertedWhat)){
            $checkExistenceProductOrProjectOk = true;
          }
        }
        if($insertedWhatKind == "project"){
          if(doesThisProjectExists($insertedWhat)){
            $checkExistenceProductOrProjectOk = true;
          }
        }
        if($checkExistenceProductOrProjectOk == true){
          //Check you have received a message in your chat to write the feedback (valid also if he has sent the notification instead)
          $expetedLinkKind = "";
          if($insertedWhatKind == "product"){
            $expetedLinkKind = "feedbackCollProd";
          }
          if($insertedWhatKind == "project"){
            $expetedLinkKind = "feedbackCollProj";
          }
          if(thisUserRecivedThisNotification($_SESSION["userId"],$expetedLinkKind,$insertedWhat) || thisUserSentThisNotification($_SESSION["userId"],$expetedLinkKind,$insertedWhat)){
            //Send feedback collaboration
            saveFeedbackCollaboration($_SESSION["userId"], $kindOfTheAccountInUse, $insertedWhat, $insertedWhatKind, $insertedFeedback);
            addParagraph(translate("Thanks for the feedback")."!");
          } else {
            addParagraph(translate("You havent clicked on the notification"));
          }
        } else {
          if($insertedWhatKind == "product"){
            addParagraph(translate("This product doesnt exists"));
          }
          if($insertedWhatKind == "project"){
            addParagraph(translate("This project doesnt exists"));
          }
        }
      }
    } else {
      if(isset($_GET["id"]) && isset($_GET["kind"])){
        upperPartOfThePage(translate("Send feedback collaboration"),"cookieBack");
        addScriptAddThisPageToChronology();
        if($_GET["kind"] == "product" || $_GET["kind"] == "project"){
          //Check existence product or project
          $checkExistenceProductOrProjectOk = false;
          if($_GET["kind"] == "product"){
            if(doesThisProductExists($_GET["id"])){
              $checkExistenceProductOrProjectOk = true;
            }
          }
          if($_GET["kind"] == "project"){
            if(doesThisProjectExists($_GET["id"])){
              $checkExistenceProductOrProjectOk = true;
            }
          }
          if($checkExistenceProductOrProjectOk == true){
            //Check you have received a message in your chat to write the feedback (valid also if he has sent the notification instead)
            $expetedLinkKind = "";
            if($_GET["kind"] == "product"){
              $expetedLinkKind = "feedbackCollProd";
            }
            if($_GET["kind"] == "project"){
              $expetedLinkKind = "feedbackCollProj";
            }
            if(thisUserRecivedThisNotification($_SESSION["userId"],$expetedLinkKind,$_GET["id"]) || thisUserSentThisNotification($_SESSION["userId"],$expetedLinkKind,$_GET["id"])){
              //Content of this page
              //Show info of the product or the project of this collaboration
              if($_GET["kind"] == "product"){
                //Show the related product
                $productInfos = obtainProductInfos($_GET["id"]);
                $fileImageToVisualize = genericProductImage;
                if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
                  $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
                }
                $text1 = translate("Category").": ".translate($productInfos["category"]).'<br>'.translate("Price").": ".floatToPrice($productInfos["price"]);
                $text2 = translate("Quantity from the owner").": ".$productInfos["quantity"];
                addACardForTheGrid("./product.php?id=".urlencode($productInfos["id"]),$fileImageToVisualize,htmlentities($productInfos["name"]),$text1,$text2);
              }
              if($_GET["kind"] == "project"){
                $projectInfos = obtainProjectInfos($_GET["id"]);
                //Show the related project
                addParagraph(translate("Project").":");
                $fileImageToVisualize = genericProjectImage;
                if(isset($projectInfos['icon']) && ($projectInfos['icon'] != null)){
                  $fileImageToVisualize = blobToFile($projectInfos["iconExtension"],$projectInfos['icon']);
                }
                $text1 = translate("Price").": ".floatToPrice($projectInfos["price"]);
                $text2 = translate("Percentage to designer").": ".$projectInfos["percentageToDesigner"]."%";
                addACardForTheGrid("./project.php?id=".urlencode($projectInfos["id"]),$fileImageToVisualize,htmlentities($projectInfos["name"]),$text1,$text2);
              }
              //Title Send feedback collaboration
              addTitle(translate("Send feedback collaboration"));
              //Form to insert data edit product general info of this product
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addLongTextField(translate("Feedback"),"insertedFeedback",2046);
              addHiddenField("insertedWhat",$_GET["id"]);
              addHiddenField("insertedWhatKind",$_GET["kind"]);
              endForm(translate("Submit"));
              ?>
                <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedFeedback = document.getElementById('insertedFeedback');

                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedFeedback.value === ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the feedback") ?>");
                    }
                  }
                </script>
              <?php
              //End main content of this page
            } else {
              addParagraph(translate("You havent clicked on the notification"));
            }
          } else {
            if($_GET["kind"] == "product"){
              addParagraph(translate("This product doesnt exists"));
            }
            if($_GET["kind"] == "project"){
              addParagraph(translate("This project doesnt exists"));
            }
          }
        } else {
          addParagraph(translate("Error in the kind of collaboration"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        //You have missed to specify some get params
        addParagraph(translate("You have missed to specify some get params"));
      }
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
