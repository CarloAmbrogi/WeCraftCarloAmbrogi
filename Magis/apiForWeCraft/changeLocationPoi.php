<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/changeLocationPoi.php?password=abcde&oldAddress=exampleAddress&oldLatitude=9&oldLongitude=99&address=exampleAddress2&latitude=8&longitude=88


if(isset($_GET["password"]) && isset($_GET["oldAddress"]) && isset($_GET["oldLatitude"]) && isset($_GET["oldLongitude"]) && isset($_GET["address"]) && isset($_GET["latitude"]) && isset($_GET["longitude"])){
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
        $latitude = $_GET["latitude"];
        if($latitude > 99.0){
            $latitude = 99.0;
        }
        if($latitude < 0.0){
            $latitude = 0.0;
        }
        $longitude = $_GET["longitude"];
        if($longitude > 999.0){
            $longitude = 999.0;
        }
        if($longitude < 0.0){
            $longitude = 0.0;
        }
        //Find that the new poi doesnt already exists
        $existingPoiFound = $database->sel_record("POI","Address = '".$_GET["address"]."' and Latitude = ".$latitude." and Longitude = ".$longitude);
        if(!isset($existingPoiFound)){
            //Find the code of the old poi
            $existingPoiFound = $database->sel_record("POI","Address = '".$_GET["oldAddress"]."' and Latitude = ".$oldLatitude." and Longitude = ".$oldLongitude);
            $existingPoiFoundCode = $existingPoiFound["CodePOI"];
            //Update poi
            $sql = "update `POI` set `Latitude` = ?, `Longitude` = ?, `Address` = ? where `CodePOI` = ?;";
            if($statement = $database->db->prepare($sql)){
                $statement->bind_param("ddsi",$latitude,$longitude,$_GET["address"],$existingPoiFoundCode);
                $statement->execute();
            } else {
                echo "Error not possible execute the query: $sql. " . $database->db->error;
            }
            //Update the address also on metadata
            $sql = "update `Metadata` set `Location` = ? where `CodePOI` = ?;";
            if($statement = $database->db->prepare($sql)){
                $statement->bind_param("si",$_GET["address"],$existingPoiFoundCode);
                $statement->execute();
            } else {
                echo "Error not possible execute the query: $sql. " . $database->db->error;
            }
        }
    }
}

?>
