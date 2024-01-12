<?php


?>

<div class="container" >

	<h5>Content Editing</h5>

<? /// echo $riga["MediaCode"] ?>

	<div class="card" style="margin-top:20px; min-height:200px; background-color:transparent;" >
		<div class="card-body" id="contentForm" >

<? include "content_form.tpl.php"; ?>

		</div>
	</div>


<div id="message" class="alert alert-success" role="alert" style="margin-bottom:40px;"></div>

</div>


<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 

<script type="text/javascript">

// if ($("#waiting").length) $("#waiting").show();
// $("#waiting").show();


var jsroot = "<?= $BASE_URL ?>";   


</script>
