<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/changeCategoryMetadata.php?password=abcde&tag=Artisan&tagEn=Artisan&tagIt=Artigiano&url=exampleUrl


if(isset($_GET["password"]) && isset($_GET["category"]) && isset($_GET["tag"]) && isset($_GET["tagEn"]) && isset($_GET["tagIt"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        //Find the media code of the metadata
        $existingMetadataFound = $database->sel_record("Metadata","URL = '".$_GET["url"]."'");
        $existingMetadataFoundMediaCode = $existingMetadataFound["MediaCode"];
        //Find the id of the tag
        $existingTagFound = $database->sel_record("Tags","Name = '".$_GET["tag"]."'");
        if(!isset($existingTagFound)){
            $sql = "insert into `Tags` (`TagID`,`Context`,`Name`,`ExprUK`,`ExprIT`) values (NULL,'WeCraft',?,?,?);";
            if($statement = $database->db->prepare($sql)){
                $statement->bind_param("sss",$_GET["tag"],$_GET["tagEn"],$_GET["tagIt"]);
                $statement->execute();
            } else {
                echo "Error not possible execute the query: $sql. " . $database->db->error;
            }
            $existingTagFound = $database->sel_record("Tags","Name = '".$_GET["tag"]."'");
        }
        $existingTagFoundId = $existingTagFound["TagID"];
        //Change the tag id assigned to the metadata
        $sql = "update `MetadataTags` set `TagID` = ? where `MediaCode` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("ii",$existingTagFoundId,$existingMetadataFoundMediaCode);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
    }
}

?>
