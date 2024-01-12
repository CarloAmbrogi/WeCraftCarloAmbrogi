<?php



?>

<div class="container" >

	<h5>Content List</h5>

<? if ($righe) { 
      $n = 0;
?>
<table class="table table-hover" style="background-color:#ddffaa;" >
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Type</th>
      <th scope="col">ProviderName</th>
      <th scope="col">Title</th>
      <th scope="col">-</th>
      <th scope="col">-</th>
    </tr>
  </thead>
  <tbody>
<?  foreach ($righe as $riga) { 
	$n++;
	$title = $riga["Title"];
	if (strlen($title) > 50) $title = substr($title,0,48) . "...";
?>
    <tr>
      <th scope="row"><?= $n ?></th>
      <td scope="row"><?= $riga["Type"] ?></td>
      <td scope="row"><?= $riga["ProviderName"] ?></td>
      <td scope="row"><?= $title ?></td>

      <th scope="row"><a type="button" class="btn btn-warning btn-sm" href="../home/edit.php?c=<?= $riga["MediaCode"] ?>" >Edit</a></th>
      <th scope="row"><a type="button" class="btn btn-info btn-sm" href="<?= $riga["URL"] ?>" target="_blank" >Open</a></th>
   </tr>

<? } ?>
  </tbody>
</table>

<? }
else echo "<h3>no data</h3>";
?>

</div>


<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 

<script type="text/javascript">

// if ($("#waiting").length) $("#waiting").show();
// $("#waiting").show();


var jsroot = "<?= $BASE_URL ?>";   

function viewItem() {

}
</script>
