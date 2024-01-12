<?php

// come la get_poi ma accessibile al pubblico


include "../database/db_connect.inc.php";
include "../include/engine.inc.php";

// session:
session_start();
$userinfo = $_SESSION["userinfo"];

// authorization:
engine_auth(array("user", "manager"));


// request:
$cid = $_POST["poiId"];
// $cid = $_POST["p1"]; // se chiamata con loadElem


// data:
if ($cid > 0) {
$poi = $database->sel_record("POI", "CodePOI = '$cid'");
$items = $database->sel_data("Metadata", "CodePOI = '$cid'");
}


// logic:
$output = "";

if ($items) { 

  $k = 0;
  $output = "<div class='accordion' id='accordion1'>";

  foreach ($items as $item) { 

	$k++;
	$title = $item["Title"];
	if (strlen($title) > 38) $title = substr($title,0,36) . "...";

	$descri = $item["Description"];
	if (strlen($descri) > 250) $descri = substr($descri,0,248) . "...";

$body = "";

$body .= "Location: " . $item["Location"] . "<br/>";
$body .= "Date: " . $item["StartDate"] . "<br/>";

$body .= '<img class="itemimg" src="' . $item["ImageURL"] . '">';

$body .= $descri . "<br/>";

$body .= "<a type='button' class='btn btn-info btn-sm' href='".$item["URL"]."' target='_new'>Open Link</a>";


	// $output .= $title . "</br>";

$output .= "<div class='accordion-item'>";
$output .= "<h2 class='accordion-header' id='heading$k'>";
$output .= "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$k' aria-expanded='true' aria-controls='collapse$k'>";
$output .= $title;
$output .= "</button>";
$output .= "</h2>";
$output .= "<div id='collapse$k' class='accordion-collapse collapse' aria-labelledby='heading$k' data-bs-parent='#accordion1'>";
$output .= "<div class='accordion-body'>";
$output .= $body;
$output .= "</div></div></div>";

  }

  $output .= '</div>'; // closing accordion

}
else $output = "No content item found";


// view:
$resp = array();
$resp["errMsg"] = false;
$resp["htm"] = $output;
// $resp = $output; // se chiamata con loadElem



// response:
echo json_encode($resp); 
// echo($resp); // se chiamata con loadElem
?>