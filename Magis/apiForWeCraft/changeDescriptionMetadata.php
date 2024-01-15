<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/changeDescriptionMetadata.php?password=abcde&description=exampleDescription2&url=exampleUrl


if(isset($_GET["password"]) && isset($_GET["description"]) && isset($_GET["url"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        //Find the media code of the metadata
        $existingMetadataFound = $database->sel_record("Metadata","URL = '".$_GET["url"]."'");
        $existingMetadataFoundMediaCode = $existingMetadataFound["MediaCode"];
        //Change the description of this metadata
        $sql = "update `Metadata` set `Description` = ? where `URL` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("ss",$_GET["description"],$_GET["url"]);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
    }
}

?>
