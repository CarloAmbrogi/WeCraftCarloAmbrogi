<?php
  include dirname(__FILE__)."/../database/access.php";
  include "./../database/functions.php";

  //Load session var
  include "./../components/includes.php";
  doInitialScripts();

  //Show the number of unread messages of this user in this chat
  //GET params: chatWith chatKind
  //user id took from session
  // example of the result:
  // [{"numberMessagesToReadInThisChat":999}]

  if(isset($_SESSION["userId"]) && isset($_GET["chatWith"]) && isset($_GET["chatKind"])){

    $userId = $_SESSION["userId"];
    $chatWith = $_GET["chatWith"];
    $chatKind = $_GET["chatKind"];

    $sql = "select count(*) as numberMessagesToReadInThisChat from (select t.messageId as messageId from ((select `Messages`.`fromWho` as chatWith, `Messages`.`id` as messageId, 'personal' as chatKind, `Messages`.`fromWho` as fromWho from `Messages` where `Messages`.`toKind` = 'personal' and `Messages`.`toWho` = ?) union (select `Messages`.`toWho` as chatWith, `Messages`.`id` as messageId, 'personal' as chatKind, `Messages`.`fromWho` as fromWho from `Messages` where `Messages`.`toKind` = 'personal' and `Messages`.`fromWho` = ?) union (select `Messages`.`toWho` as chatWith, `Messages`.`id` as messageId, 'product' as chatKind, `Messages`.`fromWho` as fromWho from `Messages` where `Messages`.`toKind` = 'product' and `Messages`.`toWho` in (select `CooperativeProductionProducts`.`product` from `CooperativeProductionProducts` where `CooperativeProductionProducts`.`user` = ?)) union (select `Messages`.`toWho` as chatWith, `Messages`.`id` as messageId, 'project' as chatKind, `Messages`.`fromWho` as fromWho from `Messages` where `Messages`.`toKind` = 'project' and `Messages`.`toWho` in (select `CooperativeProductionProjects`.`project` from `CooperativeProductionProjects` where `CooperativeProductionProjects`.`user` = ?))) as t where t.chatWith = ? and t.chatKind = ? and t.fromWho <> ? and t.messageId not in (select `messageId` from `ReadMessage` where `readBy` = ?)) as tt;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiiiisii",$userId,$userId,$userId,$userId,$chatWith,$chatKind,$userId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    $encodedData = json_encode($elements, JSON_UNESCAPED_UNICODE);

    print($encodedData);

  }

  include "../database/closeConnectionDB.php";
?>
