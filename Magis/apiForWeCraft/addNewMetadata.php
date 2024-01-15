<?php

include "../database/db_connect.inc.php";

//example:
//http://carloambrogipolimi.altervista.org/Magis/apiForWeCraft/addNewMetadata.php?password=abcde&title=exampleShopNameArtisan&description=exampleDescription&url=exampleUrl&address=exampleAddress&imageUrl=exampleImageUrl&latitude=9&longitude=99&tag=Artisan&tagEn=Artisan&tagIt=Artigiano&shopName=exampleShopNameArtisan

if(isset($_GET["password"]) && isset($_GET["title"]) && isset($_GET["description"]) && isset($_GET["url"]) && isset($_GET["address"]) && isset($_GET["imageUrl"]) && isset($_GET["latitude"]) && isset($_GET["longitude"]) && isset($_GET["tag"]) && isset($_GET["tagEn"]) && isset($_GET["tagIt"]) && isset($_GET["shopName"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
        // $_GET["tag"] is Artisan or None category or a category of a product
        //Adjust decimal limits
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
        //Find the code of the poi
        $existingPoiFound = $database->sel_record("POI","Address = '".$_GET["address"]."' and Latitude = ".$latitude." and Longitude = ".$longitude);
        if(!isset($existingPoiFound)){
            $sql = "insert into `POI` (`CodePOI`,`Name`,`Latitude`,`Longitude`,`Elevation`,`TypePOI`,`Surface`,`Address`) values (NULL,?,?,?,0,'PoiWeCraft',0,?);";
            if($statement = $database->db->prepare($sql)){
                $statement->bind_param("sdds",$_GET["shopName"],$latitude,$longitude,$_GET["address"]);
                $statement->execute();
            } else {
                echo "Error not possible execute the query: $sql. " . $database->db->error;
            }
            $existingPoiFound = $database->sel_record("POI","Address = '".$_GET["address"]."' and Latitude = ".$latitude." and Longitude = ".$longitude);
        }
        $existingPoiFoundCode = $existingPoiFound["CodePOI"];
        //Insert the new metadata
        $sql = "insert into `Metadata` (`MediaCode`,`Title`,`Description`,`URL`,`Type`,`PublicationDate`,`StartDate`,`EndDate`,`Location`,`TagsFound`,`TrainingTags`,`ImageURL`,`ProviderName`,`ProviderURL`,`ProviderIcon`,`CodePOI`,`TrainingText`,`Usage`,`TagsAlgorithm`) values (NULL,?,?,?,'link',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),?,'WeCraft','WeCraft',?,'WeCraft','http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php','',?,'','WeCraft','WeCraft');";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("sssssi",$_GET["title"],$_GET["description"],$_GET["url"],$_GET["address"],$_GET["imageUrl"],$existingPoiFoundCode);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
        //Obtain the media code of the metadata
        $existingMetadataFound = $database->sel_record("Metadata","URL = '".$_GET["url"]."'");
        $existingMetadataFoundMediaCode = $existingMetadataFound["MediaCode"];
        //Assign the tag to this new metadata
        $sql = "insert into `MetadataTags` (`MediaCode`,`TagID`,`Source`) values (?,?,'SVM_02');";
        if($statement = $database->db->prepare($sql)){
            $statement->bind_param("ii",$existingMetadataFoundMediaCode,$existingTagFoundId);
            $statement->execute();
        } else {
            echo "Error not possible execute the query: $sql. " . $database->db->error;
        }
    }
}

?>
