<?php

// restituisce la lista dei POIs corrispondente ai criteri di selezione che riceve


include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
/// engine_auth(array("user", "manager"));


// request:
$time1 = $_POST["time1"]; // es. '2020-06-30T00:00:00.000Z'
$time2 = $_POST["time2"];

$tags = $_POST["tags"];
$tags = str_replace("chk_","",$tags);




$elenco = "";
$resultset = "";

function Discendere ($nodeset) {

   global $database;
   global $resultset;

   foreach ($nodeset as $nodo) {

      $resultset .= $nodo . ",";

      $tag = $database->sel_record("Tags", "TagID = '$nodo'");
      $tagName = $tag["Name"];

      $query = "SELECT TagID FROM Tags JOIN Ontology ON Tags.Name = Ontology.Tag1 WHERE Relation = 'is_a' and Tag2 = '$tagName'";
      $righe = $database->select($query);

      $q = 0;
      $children = array();
      foreach ($righe as $riga) {

         $children[] = $riga["TagID"];
         $q++;
      }

      if ($q > 0) Discendere ($children);
   }

} // end function



if ($tags == "()") $elenco = "()";
else {

   $nodi = array();
   // $nodi[] = '100'; // test

$strNodi = substr($tags, 0, -1);
$strNodi = substr($strNodi, 1);
$nodi = explode(',', $strNodi);

   Discendere($nodi);

   $elenco = "(" . $resultset . ")";
   $elenco = str_replace(",)", ")", $elenco);
}



// data:

$query = "select distinct CodePOI from Metadata";
$query .= " where PublicationDate between '$time1' and '$time2'";
if (true) {
   $query .= " and Metadata.MediaCode in ";
   //$query .= " (SELECT MediaCode FROM MetadataTags JOIN Tags ON MetadataTags.TagID = Tags.TagID WHERE Tags.TagID in $elenco )";
   $query .= " (SELECT MediaCode FROM MetadataTags JOIN Tags ON MetadataTags.TagID = Tags.TagID WHERE true )";
}

// $pois = $database->sel_data("POI", "CodePOI > 0 and CodePOI < 100"); // TEST
$pois = $database->select($query);




// logic:

/*
$poid_list = "[";
foreach ($pois as $apoi) { // lista di tutti i PoIs 
   $poid_list .= $apoi["CodePOI"] . ",";
}
$poid_list .= "]";
*/

$poid_list = array();
foreach ($pois as $apoi) { // lista di tutti i PoIs 
   $poid_list[] = $apoi["CodePOI"];
}



$output = $poid_list;
// $output = $query; // DEBUG



// view:

$resp = array();
$resp["errMsg"] = false;
$resp["poilist"] = $output;
$resp["debug"] = $query;


// response:

echo json_encode($resp); 
?>