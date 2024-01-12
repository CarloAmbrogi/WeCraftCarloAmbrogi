<?php

// request:
$param1 = $_POST["p1"];
$param2 = $_POST["p2"];
$securecode = $_POST["s1"];

if ($securecode != '43210') die ('error: invalid call');




/*

// accesso alla sessione:

// accesso ai dati:

*/




include "../libs/embed/autoloader.php";

use Embed\Embed;

// Load url:

$link = $param1;
if (!$link) die("Link non valido!");


$info = Embed::create($link);

/*

$info->title; //The page title
$info->description; //The page description
$info->url; //The canonical url
$info->type; //The page type (link, video, image, rich)
$info->tags; //The page keywords (tags)

$info->images; //List of all images found in the page
$info->image; //The image choosen as main image
$info->imageWidth; //The width of the main image
$info->imageHeight; //The height of the main image

$info->code; //The code to embed the image, video, etc
$info->width; //The width of the embed code
$info->height; //The height of the embed code
$info->aspectRatio; //The aspect ratio (width/height)

$info->authorName; //The resource author
$info->authorUrl; //The author url

$info->providerName; //The provider name of the page (Youtube, Twitter, Instagram, etc)
$info->providerUrl; //The provider url
$info->providerIcons; //All provider icons found in the page
$info->providerIcon; //The icon choosen as main icon

$info->publishedDate; //The published date of the resource <<< publishedTime ?
$info->license; //The license url of the resource
$info->linkedData; //The linked-data info (http://json-ld.org/)
$info->feeds; //The RSS/Atom feeds

*/


?>
<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-8">



		<div class="card" style="background-color:#ffffaa;" >
			<div class="card-body" >


<form id="editform" >

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Title</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" name="Title" value="<?= $info->title ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtUrl" class="col-sm-3 col-form-label">Url</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtUrl" value="<?= $info->url ?>" disabled >
      <input type="hidden" name="URL" value="<?= $info->url ?>" > 
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtType" class="col-sm-3 col-form-label">Type</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtType" name="Type" value="<?= $info->type ?>" >
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtImage" class="col-sm-3 col-form-label">Image</label>
    <div class="col-sm-9">
      <img src="<?= $info->image ?>" style="max-width:500px;" >
      <div class="form-text"><?= $info->image ?></div>
      <input type="hidden" name="ImageURL" value="<?= $info->image ?>" > 
    </div>
  </div>

<!--
  <div class="row mb-3">
    <label for="txtTags" class="col-sm-3 col-form-label">Images</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="txtTags" rows="3" disabled><?= print_r($info->images, true); ?></textarea>
    </div>
  </div>
-->

  <div class="row mb-3">
    <label for="Descri" class="col-sm-3 col-form-label">Description</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="Descri" name="Description" rows="3"><?= $info->description ?></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTags" class="col-sm-3 col-form-label">Tags</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="txtTags" name="TagsFound" rows="3">
<?
foreach ($info->tags as $item) {
   // echo $item . "; ";
   echo $item . "\n";
}
?>
</textarea>
    </div>
  </div>



  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Author</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" value="<?= $info->authorName ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Provider</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" name="ProviderName" value="<?= $info->providerName ?>">
      <input type="hidden" name="ProviderURL" value="<?= $info->providerUrl ?>" > 
      <div class="form-text"><?= $info->providerUrl ?></div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Date Published</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" name="PublicationDate" value="<?= $info->publishedTime ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">License</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" value="<?= $info->license ?>">
    </div>
  </div>


  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Media Content</label>
    <div class="col-sm-9" style="background-color:white; padding:12px;">
      <div><?= $info->code ?></div>
    </div>
  </div>


</form>
			</div>



<div class="card-footer" style="text-align:center; background-color:white;" >
  <button class="btn btn-success btn-lg" onclick="doform('upload')">Save</button>
</div>

		</div><!-- end card -->



	</div>
	<div class="col-sm-2" style="text-align:center;">		
	</div>
</div>


