<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Chat
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Chat"),"cookieBack");
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    //Check get params available
    if(isset($_GET["chatWith"]) && isset($_GET["chatKind"])){
      $youAreChattingWithACustomer = false;
      //Chech get params are correct
      $checkIsOk = false;
      if($_GET["chatKind"] == "personal"){
        $kindUserChatWith = getKindOfThisAccount($_GET["chatWith"]);
        if($_GET["chatWith"] == $_SESSION["userId"]){
          $checkIsOk = true;//chat with yourself
        }
        if($kindUserChatWith == "Designer" || $kindUserChatWith == "Artisan"){
          $checkIsOk = true;
        }
        if($kindUserChatWith == "Customer" && canYouSendMessagesToThisCustomer($_SESSION["userId"],$_GET["chatWith"])){
          $youAreChattingWithACustomer = true;
          $checkIsOk = true;
        }
      }
      if($_GET["chatKind"] == "product"){
        if(isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$_GET["chatWith"])){
          $checkIsOk = true;
        }
      }
      if($_GET["chatKind"] == "project"){
        if(isThisUserCollaboratingForTheDesignOfThisProject($_SESSION["userId"],$_GET["chatWith"])){
          $checkIsOk = true;
        }
      }
      if($checkIsOk == true){
        addScriptAddThisPageToCronology();
        //Content of the page chat
        //Show with who you are chatting
        addParagraph(translate("You are chatting with").":");
        if($_GET["chatKind"] == "personal"){
          $kindUserChatWith = getKindOfThisAccount($_GET["chatWith"]);
          $fileImageToVisualize = genericUserImage;
          $userInfos = obtainUserInfos($_GET["chatWith"]);
          if($userInfos["isActive"] == 0){
            addParagraph(translate("This is a deleted account")."!");
          }
          $title = $userInfos["name"]." ".$userInfos["surname"];
          $text1 = translate($kindUserChatWith);
          if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
          }
          $link = "./chat.php?chatKind=".urlencode($_GET["chatKind"])."&chatWith=".urlencode($_GET["chatWith"]);
          if($kindUserChatWith == "Artisan"){
            $link = "./artisan.php?id=".urlencode($_GET["chatWith"]);
          }
          if($kindUserChatWith == "Designer"){
            $link = "./designer.php?id=".urlencode($_GET["chatWith"]);
          }
          addACard($link,$fileImageToVisualize,htmlentities($title),htmlentities($text1),"");
        }
        if($_GET["chatKind"] == "product"){
          $fileImageToVisualize = genericProductImage;
          $productInfos = obtainProductInfos($_GET["chatWith"]);
          $title = $productInfos["name"];
          $text1 = translate("Product");
          if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
          }
          addACard("./cooperativeDesignProduct.php?id=".urlencode($_GET["chatWith"]),$fileImageToVisualize,htmlentities($title),htmlentities($text1),"");
        }
        if($_GET["chatKind"] == "project"){
          $fileImageToVisualize = genericProjectImage;
          $projectInfos = obtainProjectInfos($_GET["chatWith"]);
          $title = $projectInfos["name"];
          $text1 = translate("Project");
          if(isset($projectInfos['icon']) && ($projectInfos['icon'] != null)){
            $fileImageToVisualize = blobToFile($projectInfos["iconExtension"],$projectInfos['icon']);
          }
          addACard("./cooperativeDesignProject.php?id=".urlencode($_GET["chatWith"]),$fileImageToVisualize,htmlentities($title),htmlentities($text1),"");
        }
        //Mark all messages as read
        markMessagesInThisChatRead($_SESSION["userId"],$_GET["chatWith"],$_GET["chatKind"]);
        //Show the button to send a new message or to reload the chat (for designers show also to create a project)
        startDivShowHide("sendNewMessage");
        addButtonLink(translate("Send new message"),"./sendMessage.php?chatKind=".urlencode($_GET["chatKind"])."&chatWith=".urlencode($_GET["chatWith"]));
        if($kindOfTheAccountInUse == "Designer" && $youAreChattingWithACustomer){
          addButtonLink(translate("Create a project for this customer"),"./createAProject.php?id=".urlencode($_GET["chatWith"]));
        }
        endDivShowHide("sendNewMessage");
        startDivShowHide("reloadThisChat");
        addParagraph(translate("In the meantime you have received new messages in this chat"));
        addButtonLink(translate("Show new messages"),"./chat.php?chatKind=".urlencode($_GET["chatKind"])."&chatWith=".urlencode($_GET["chatWith"]));
        endDivShowHide("reloadThisChat");
        ?>
          <script>
            //At the beginning the send new message button is shown
            showHideElementsendNewMessage.style.display = "block";

            //Periodically check if are there new messages
            let intervalIdCheckIfNewMessages = setInterval(checkIfNewMessages, 5000);

            function checkIfNewMessages(){
              let requestUrl = "<?= WeCraftBaseUrl ?>api/numberUnreadMessagesInThisChat.php?chatWith=" + encodeURIComponent(<?= $_GET["chatWith"] ?>) + "&chatKind=" + encodeURIComponent('<?= $_GET["chatKind"] ?>');
              let request = new XMLHttpRequest();
              request.open("GET", requestUrl);
              request.responseType = "json";
              request.send();
              request.onload = function(){
                const result = request.response;
                if(result[0].numberMessagesToReadInThisChat > 0){
                  showHideElementsendNewMessage.style.display = "none";
                  showHideElementreloadThisChat.style.display = "block";
                  clearInterval(intervalIdCheckIfNewMessages);
                }
              }
            }
          </script>
        <?php
        //Show the messages
        $previewChat = obtainPreviewChat($_SESSION["userId"],$_GET["chatWith"],$_GET["chatKind"]);
        foreach($previewChat as &$singleMessagePreview){
          startSquare();
          $userInfosThisFrom = obtainUserInfos($singleMessagePreview["fromWho"]);
          $textParagraphFrom = translate("From").": ".$userInfosThisFrom["name"]." ".$userInfosThisFrom["surname"];
          if($singleMessagePreview["fromWho"] == $_SESSION["userId"]){
            $textParagraphFrom.=" (".translate("you").")";
          }
          addParagraphWithoutMb3($textParagraphFrom);
          $textParagraphWhen = $singleMessagePreview["timestamp"];
          if($singleMessagePreview["isANotification"] == 1){
            $textParagraphWhen.=" (".translate("Automatic message").")";
          }
          addParagraphWithoutMb3($textParagraphWhen);
          if($singleMessagePreview["text"] != ""){
            if($singleMessagePreview["isANotification"] == 1){
              $extraText = "";
              if(isset($singleMessagePreview["extraText"]) && $singleMessagePreview["extraText"] != null){
                $extraText = $singleMessagePreview["extraText"];
              }
              addParagraphWithoutMb3(translate($singleMessagePreview["text"]).$extraText);
            } else {
              addParagraphWithoutMb3Unsafe(adjustTextWithLinks($singleMessagePreview["text"]));
            }
          }
          if(isset($singleMessagePreview["image"]) && $singleMessagePreview["image"] != null){
            addImage(blobToFile($singleMessagePreview["imgExtension"],$singleMessagePreview['image']),"Image");
          }
          if(isset($singleMessagePreview["linkTo"]) && $singleMessagePreview["linkTo"] != null){
            $linkKind = $singleMessagePreview["linkKind"];
            if($linkKind == "artisan"){
              $artisanInfosUser = obtainUserInfos($singleMessagePreview["linkTo"]);
              $artisanInfosArtisan = obtainArtisanInfos($singleMessagePreview["linkTo"]);
              $fileImageToVisualizeArtisan = genericUserImage;
              if(isset($artisanInfosUser['icon']) && ($artisanInfosUser['icon'] != null)){
                $fileImageToVisualizeArtisan = blobToFile($artisanInfosUser["iconExtension"],$artisanInfosUser['icon']);
              }
              $numberOfProductsOfThisArtisan = getNumberOfProductsOfThisArtisan($singleMessagePreview["linkTo"]);
              addACard("./artisan.php?id=".urlencode($singleMessagePreview["linkTo"]),$fileImageToVisualizeArtisan,htmlentities($artisanInfosUser["name"]." ".$artisanInfosUser["surname"]),htmlentities($artisanInfosArtisan["shopName"]),translate("Total products of this artsan").": ".$numberOfProductsOfThisArtisan);
            }
            if($linkKind == "project"){
              $projectLinkInfos = obtainProjectInfos($singleMessagePreview["linkTo"]);
              $fileImageToVisualize = genericProjectImage;
              if(isset($projectLinkInfos['icon']) && ($projectLinkInfos['icon'] != null)){
                $fileImageToVisualize = blobToFile($projectLinkInfos["iconExtension"],$projectLinkInfos['icon']);
              }
              $text1 = translate("Price").": ".floatToPrice($projectLinkInfos["price"]);
              $text2 = translate("Percentage to designer").": ".$projectLinkInfos["percentageToDesigner"]."%";
              addACard("./project.php?id=".urlencode($singleMessagePreview["linkTo"]),$fileImageToVisualize,htmlentities($projectLinkInfos["name"]),$text1,$text2);
            }
            if($linkKind == "product"){
              $productLinkInfos = obtainProductInfos($singleMessagePreview["linkTo"]);
              $fileImageToVisualize = genericProductImage;
              if(isset($productLinkInfos['icon']) && ($productLinkInfos['icon'] != null)){
                $fileImageToVisualize = blobToFile($productLinkInfos["iconExtension"],$productLinkInfos['icon']);
              }
              $text1 = translate("Category").": ".translate($productLinkInfos["category"]).'<br>'.translate("Price").": ".floatToPrice($productLinkInfos["price"]);
              $text2 = "";
              addACard("./product.php?id=".urlencode($singleMessagePreview["linkTo"]),$fileImageToVisualize,htmlentities($productLinkInfos["name"]),$text1,$text2);
            }
            if($linkKind == "feedbackCollProd"){
              $productLinkInfos = obtainProductInfos($singleMessagePreview["linkTo"]);
              $fileImageToVisualize = genericProductImage;
              if(isset($productLinkInfos['icon']) && ($productLinkInfos['icon'] != null)){
                $fileImageToVisualize = blobToFile($productLinkInfos["iconExtension"],$productLinkInfos['icon']);
              }
              $text1 = translate("Category").": ".translate($productLinkInfos["category"]).'<br>'.translate("Price").": ".floatToPrice($productLinkInfos["price"]);
              $text2 = translate("Click here to write your feedback");
              addACard("./sendFeedbackCollaboration.php?id=".urlencode($singleMessagePreview["linkTo"])."&kind=product",$fileImageToVisualize,htmlentities($productLinkInfos["name"]),$text1,$text2);
            }
            if($linkKind == "feedbackCollProj"){
              $projectLinkInfos = obtainProjectInfos($singleMessagePreview["linkTo"]);
              $fileImageToVisualize = genericProjectImage;
              if(isset($projectLinkInfos['icon']) && ($projectLinkInfos['icon'] != null)){
                $fileImageToVisualize = blobToFile($projectLinkInfos["iconExtension"],$projectLinkInfos['icon']);
              }
              $text1 = translate("Price").": ".floatToPrice($projectLinkInfos["price"])." ".translate("Percentage to designer").": ".$projectLinkInfos["percentageToDesigner"]."%";
              $text2 = translate("Click here to write your feedback");
              addACard("./sendFeedbackCollaboration.php?id=".urlencode($singleMessagePreview["linkTo"])."&kind=project",$fileImageToVisualize,htmlentities($projectLinkInfos["name"]),$text1,$text2);
            }
          }
          endSquare();
        }
      } else {
        //Show error you cant chat here
        if($_GET["chatKind"] == "personal"){
          if($kindOfTheAccountInUse == "Customer"){
            addParagraph(translate("A customer cant chat with other customers"));
          } else {
            addParagraph(translate("You cant chat with customer who hasnt started a chat with you"));
          }
        } else if($_GET["chatKind"] == "product" || $_GET["chatKind"] == "project"){
          addParagraph(translate("You are not in this group for this cooperative design"));
        } else {
          addParagraph(translate("This kind of chat doesnt exists"));
        }
      }
    } else {
      addParagraph(translate("You have missed to pass the get params"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
