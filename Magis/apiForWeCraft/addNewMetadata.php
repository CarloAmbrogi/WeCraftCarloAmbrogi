<?php

include "../database/db_connect.inc.php";

if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){
echo "aaa";
}

if(isset($_GET["password"]) && isset($_GET["name"]) && isset($_GET["description"]) && isset($_GET["url"]) && isset($_GET["address"]) && isset($_GET["imageUrl"]) && isset($_GET["latitude"]) && isset($_GET["longitude"]) && isset($_GET["tag"])){
    if($_GET["password"] == $PASSWORD_CONNECTION_WECRAFT_MAGIS){

    }
}

?>
