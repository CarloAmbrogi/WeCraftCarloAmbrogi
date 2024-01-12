<?php



?>
<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-8">



		<div class="card" style="background-color:#ffffaa;" >
			<div class="card-body" >


<form id="editform" >

<input type="hidden" name="MediaCode" value="<?= $riga["MediaCode"] ?>" >


  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Title</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtTitle" name="Title" value="<?= $riga["Title"] ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtUrl" class="col-sm-3 col-form-label">Url</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtUrl" name="URL" value="<?= $riga["URL"] ?>" disabled >
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtType" class="col-sm-3 col-form-label">Type</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtType" name="Type" value="<?= $riga["Type"] ?>"    >
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtImage" class="col-sm-3 col-form-label">Image</label>
    <div class="col-sm-9">
      <img src="<?= $riga["ImageURL"] ?>" style="max-width:500px;" >
      <div class="form-text"><?= $info->image ?></div> 
    </div>
  </div>

<!--
  <div class="row mb-3">
    <label for="txtTags" class="col-sm-3 col-form-label">Images</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="txtImages" rows="3" disabled><?= print_r($info->images, true); ?></textarea>
    </div>
  </div>
-->

  <div class="row mb-3">
    <label for="Descri" class="col-sm-3 col-form-label">Description</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="Description" name="Description" rows="3"><?= $riga["Description"] ?></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTags" class="col-sm-3 col-form-label">Tags</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="txtTags" name="TagsFound" rows="3"><?= $riga["TagsFound"] ?></textarea>
    </div>
  </div>


  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Author</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtAuthor" value="<?= $info->authorName ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Provider</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="ProviderName" name="ProviderName" value="<?= $riga["ProviderName"] ?>">
      <input type="hidden" name="ProviderURL" value="<?= $riga["ProviderURL"]  ?>" > 
      <div class="form-text"><?= $info->providerUrl ?></div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Date Published</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="PublicationDate" name="PublicationDate" value="<?= $riga["PublicationDate"] ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Start Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="StartDate" name="StartDate" value="<?= $riga["StartDate"] ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">End Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="EndDate" name="EndDate" value="<?= $riga["EndDate"] ?>">
    </div>
  </div>


  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">License</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="txtLicense" value=" ">
    </div>
  </div>

<!--
  <div class="row mb-3">
    <label for="txtTitle" class="col-sm-3 col-form-label">Media Content</label>
    <div class="col-sm-9" style="background-color:white; padding:12px;">
      <div></div>
    </div>
  </div>
-->


  <div class="row mb-3" >
    <label for="txtTitle" class="col-sm-3 col-form-label">Semantic Tags</label>
    <div class="col-sm-9">
      <input type="text" style="background-color:#bbffff;" class="form-control" name="TrainingTags" id="TrainingTags" value="<?= $riga["TrainingTags"] ?>">
    </div>
  </div>


</form>
			</div>



<div class="card-footer" style="text-align:center; background-color:white;" >
  <button class="btn btn-success btn-lg" onclick="doform('edit')">Save</button>
  <a class="btn btn-primary btn-lg" href="./list.php">Back</a>
</div>

		</div><!-- end card -->



	</div>
	<div class="col-sm-2" style="text-align:center;">		
	</div>
</div>


