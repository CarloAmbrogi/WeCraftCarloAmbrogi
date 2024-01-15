<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/changeTitleMetadata.php?password=abcde&title=exampleShopNameArtisan2&url=exampleUrl


if(isset($_GET["password"]) && isset($_GET["title"]) && isset($_GET["url"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        //Find the media code of the metadata
        $existingMetadataFound = $database->sel_record("Metadata","URL = '".$_GET["url"]."'");
        $existingMetadataFoundMediaCode = $existingMetadataFound["MediaCode"];
        $existingMetadataFoundCodePoi = $existingMetadataFound["CodePOI"];
        //Change the title of this metadata
        $sql = "update `Metadata` set `Title` = ? where `URL` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("ss",$_GET["title"],$_GET["url"]);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
        //In case of artisan it means that the title is also the name of the shop and so update also the name of the corresponding poi
        if(str_contains($_GET["url"],"artisan")){
            //Update poi
            $sql = "update `POI` set `Name` = ? where `CodePOI` = ?;";
            if($statement = $database->db->prepare($sql)){
                $statement->bind_param("si",$_GET["title"],$existingMetadataFoundCodePoi);
                $statement->execute();
            } else {
                echo "Error not possible execute the query: $sql. " . $database->db->error;
            }
        }
    }
}

?>
