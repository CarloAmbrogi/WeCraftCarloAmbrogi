<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/deletePoi.php?password=abcde&oldAddress=exampleAddress&oldLatitude=9&oldLongitude=99


if(isset($_GET["password"]) && isset($_GET["oldAddress"]) && isset($_GET["oldLatitude"]) && isset($_GET["oldLongitude"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        //Adjust decimal limits
        $oldLatitude = $_GET["oldLatitude"];
        if($oldLatitude > 99.0){
            $oldLatitude = 99.0;
        }
        if($oldLatitude < 0.0){
            $oldLatitude = 0.0;
        }
        $oldLongitude = $_GET["oldLongitude"];
        if($oldLongitude > 999.0){
            $oldLongitude = 999.0;
        }
        if($oldLongitude < 0.0){
            $oldLongitude = 0.0;
        }
        //Find the code of the old poi
        $existingPoiFound = $database->sel_record("POI","Address = '".$_GET["oldAddress"]."' and Latitude = ".$oldLatitude." and Longitude = ".$oldLongitude);
        $existingPoiFoundCode = $existingPoiFound["CodePOI"];
        //Delete poi
        $sql = "delete from `POI` where `CodePOI` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("i",$existingPoiFoundCode);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
        //Delete MetadataTags where metadata with that poi
        $sql = "delete from `MetadataTags` where `MediaCode` in (select `MediaCode` from `Metadata` where `CodePOI` = ?);";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("i",$existingPoiFoundCode);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
        //Delete metadata with that poi
        $sql = "delete from `Metadata` where `CodePOI` = ?;";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("i",$existingPoiFoundCode);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
    }
}

?>
