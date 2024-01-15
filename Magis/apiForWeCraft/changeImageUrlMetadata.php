<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/changeImageUrlMetadata.php?password=abcde&imageUrl=exampleImageUrl2&url=exampleUrl


if(isset($_GET["password"]) && isset($_GET["imageUrl"]) && isset($_GET["url"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        //Find the media code of the metadata
        $existingMetadataFound = $database->sel_record("Metadata","URL = '".$_GET["url"]."'");
        $existingMetadataFoundMediaCode = $existingMetadataFound["MediaCode"];
        //Change the image url of this metadata
        $sql = "update `Metadata` set `ImageURL` = ? where `URL` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("ss",$_GET["imageUrl"],$_GET["url"]);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
    }
}

?>
