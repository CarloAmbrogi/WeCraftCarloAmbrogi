<?php



?>

<div class="container" >



<? 

if ($righe) { 

   $n = 0;



   foreach ($righe as $riga) { 

	$n++;
	$title = $riga["Title"];
	if (strlen($title) > 50) $title = substr($title,0,48) . "...";


$testo = "";

if ( $riga["Title"] == $riga["Description"] ) $testo .= $riga["Title"];
else $testo .= $riga["Title"] . " " . $riga["Description"];

$testo .= " " . $riga["TagsFound"];


$t1 = $riga["URL"];
$p1 = strrpos($riga["URL"],"/");
$t1 = substr($t1,1+$p1);
$t1 = str_replace("-"," ",$t1);
$t1 = str_replace(".html"," ",$t1);
$testo .= " " . $t1;




if (strlen($testo) > 499) {

   $t2 = substr($testo,0,499);
   $p2 = strrpos($t2," ");
   $testo = substr($testo,0,$p2);

   /// echo $p2."<br>";
}




$testo = str_replace(","," ",$testo);
$testo = str_replace(";"," ",$testo);



   echo $riga["MediaCode"] . ") " . $testo . "<br/><br/>";
   /// echo strlen($testo) . "<br/><br/>";



// save into DB
$valori = array();
$valori["MediaCode"] = $riga["MediaCode"];
$valori["TrainingText"] = $testo;
/// $esito = $database->do_update ($valori, "Metadata", "MediaCode", true); // debug
$esito = $database->do_update ($valori, "Metadata", "MediaCode");






   /// if ($n > 1) break;

   } 
}
else echo "<h3>no data</h3>";

?>

</div>


<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 

<script type="text/javascript">

var jsroot = "<?= $BASE_URL ?>";   

function viewItem() {

}
</script>
