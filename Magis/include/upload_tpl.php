<?php


?>

<div class="container" >

	<h5>Content Upload</h5>


	<div class="card" style="background-color:#ddffaa;" >
	<div class="card-body" >
	<div class="mb-3">
		<label for="txtLink" class="form-label">Link URL</label>

		<div class="row">
			<div class="col-9">
				<input type="text" class="form-control" id="txtLink" aria-describedby="txtLinkHelptext">
			</div>
			<div class="col-3" style="text-align:center;">
				<button type="submit" class="btn btn-info" onClick="doFetchUrl()" >Fetch Content</button>
			</div>
		</div>

		<div id="txtLinkHelptext" class="form-text">Web URL of a new content page to scan</div>
	</div>
	</div>
	</div>



	<div class="card" style="margin-top:20px; min-height:200px; background-color:transparent;" >
		<div class="card-body" id="contentForm" >
		</div>
	</div>


</div>


<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 

<script type="text/javascript">

// if ($("#waiting").length) $("#waiting").show();
// $("#waiting").show();


var jsroot = "<?= $BASE_URL ?>";   

function doFetchUrl() {
  loadElem ('#contentForm', 'ajax/fetchurl.php', $("#txtLink").val(), 2 );
}
</script>
