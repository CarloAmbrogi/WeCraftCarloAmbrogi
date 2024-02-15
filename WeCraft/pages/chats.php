<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Chats
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Chats"),"");
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    addScriptAddThisPageToChronology();
    //Content of the page chats
    $previewChatList = obtainPreviewChatList($_SESSION["userId"]);
    startCardGrid();
    $atLeastOneChat = false;
    foreach($previewChatList as &$singleChatPreview){
      $atLeastOneChat = true;
      $title = "";
      $text1 = "";
      $fileImageToVisualize = genericUserImage;
      if($singleChatPreview["chatKind"] == "personal"){
        $userInfos = obtainUserInfos($singleChatPreview["chatWith"]);
        $title = $userInfos["name"]." ".$userInfos["surname"];
        $text1 = translate(getKindOfThisAccount($singleChatPreview["chatWith"]));
        if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
          $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
        }
      }
      if($singleChatPreview["chatKind"] == "product"){
        $fileImageToVisualize = genericProductImage;
        $productInfos = obtainProductInfos($singleChatPreview["chatWith"]);
        $title = $productInfos["name"];
        $text1 = translate("Product");
        if(isset($productInfos['icon']) && ($productInfos['icon'] != null)){
          $fileImageToVisualize = blobToFile($productInfos["iconExtension"],$productInfos['icon']);
        }
      }
      if($singleChatPreview["chatKind"] == "project"){
        $fileImageToVisualize = genericProjectImage;
        $projectInfos = obtainProjectInfos($singleChatPreview["chatWith"]);
        $title = $projectInfos["name"];
        $text1 = translate("Project");
        if(isset($projectInfos['icon']) && ($projectInfos['icon'] != null)){
          $fileImageToVisualize = blobToFile($projectInfos["iconExtension"],$projectInfos['icon']);
        }
      }
      $numberMessagesToRead = numberMessagesToReadInThisChat($_SESSION["userId"],$singleChatPreview["chatWith"],$singleChatPreview["chatKind"]);
      $text2 = translate("Number unread messages").": ".$numberMessagesToRead;
      if($numberMessagesToRead == 0){
        $text2 = translate("All messages read");
      }
      addACardForTheGrid("./chat.php?chatKind=".urlencode($singleChatPreview["chatKind"])."&chatWith=".urlencode($singleChatPreview["chatWith"]),$fileImageToVisualize,htmlentities($title),htmlentities($text1),htmlentities($text2));
    }
    endCardGrid();
    if($atLeastOneChat == false){
      addParagraph(translate("You havent started any chat"));
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
