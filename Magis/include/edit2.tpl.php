<?php


?>
<style>
div.map-div {
 height: 400px;
 border: 1px solid gray;
}
</style>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Points Of Interest</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color:#dfa;">
      <div id="map2" class="map-div"></div>
      </div>
      <div class="modal-footer">
        <div class="col-auto" style="position:absolute; left:20px;" >
          <form id="poiform">
          <input type="hidden" name="CodePOI"   id="PoiCode" val="0">
          <input type="hidden" name="Latitude"  id="PoiLat"  val="0">
          <input type="hidden" name="Longitude" id="PoiLng"  val="0">
          <label for="PoiTxt" class="visually-hidden" for="autoSizingInput">Name</label>
          <input type="text" name="Name" class="form-control" id="PoiTxt" placeholder="POI name" value="<?= $poi["Name"] ?>">
          </form>
        </div>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" type="button" onclick="savepoi()">Save</button>
        <button type="button" class="btn btn-info" type="button" onclick="newpoi()" id="newpoi_btn" >New</button>
        <button type="button" class="btn btn-danger" type="button" onclick="delpoi()" id="delpoi_btn" disabled >Delete</button>
      </div>
    </div>
  </div>
</div>




<div class="container" >

	<h5>Content Editing</h5>

<? /// echo $riga["MediaCode"] ?>

	<div class="card" style="margin-top:20px; min-height:200px; background-color:transparent;" >
		<div class="card-body" id="contentForm" >

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




  <div class="card card-body" style="background:#dfa; min-height:410px; margin-bottom:20px;">
    <div class="row mb-3" >
      <label for="CodePOI" class="col-sm-3 col-form-label" >Georeference</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="CodePOItxt" value="<?= $poi["Name"] ?>" disabled>
        <input type="hidden" class="form-control" name="CodePOI" id="CodePOI" value="<?= $riga["CodePOI"] ?>" >
      </div>
    </div>
    <div id="map1" class="map-div"></div>
    <div class="text-center" style="margin-top:10px;" id="POI_btns">
<? if ($riga["CodePOI"]) { ?>
      <button id="btnEdit" class="btn btn-warning btn-sm" type="button" onclick="editpoi()">Edit POI</button>
      <button id="btnClear" class="btn btn-danger btn-sm"  type="button" onclick="clearpoi()">Clear POI</button>
      <button id="btnSet" class="btn btn-info btn-sm d-none" type="button" onclick="setpoi()">Set POI</button>
<? } else { ?>
      <button id="btnEdit" class="btn btn-warning btn-sm d-none" type="button" onclick="editpoi()">Edit POI</button>
      <button id="btnClear" class="btn btn-danger btn-sm d-none"  type="button" onclick="clearpoi()">Clear POI</button>
      <button id="btnSet" class="btn btn-info btn-sm" type="button" onclick="setpoi()">Set POI</button>
<? } ?>
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


		</div>
	</div>


<div id="message" class="alert alert-success" role="alert" style="margin-bottom:40px;"></div>

</div>


<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 

<!-- leaflet maps -->
<script src="http://www.mixmap.it/magis/jslibs/leaflet/leaflet.js"></script> 
<script src="http://www.mixmap.it/magis/jslibs/leaflet/plugins/extramarkers/js/leaflet.extra-markers.min.js"></script> 
<script src="http://www.mixmap.it/magis/jslibs/leaflet/plugins/geocoder/Control.OSMGeocoder.js"></script>

<!-- jquery UI (CDN) (required ??) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/maps.js"></script> 

<script type="text/javascript">

var jsroot = "<?= $BASE_URL ?>";   

// do_geolocate();

// var myModal = new bootstrap.Modal(document.getElementById('myModal'), options);
var myModal = new bootstrap.Modal(document.getElementById('myModal') );

<?
if ($riga["CodePOI"]) {
   $poi_text = $poi["Name"];
   echo 'map1 = load_map ("map1", '.$poi["Latitude"].', '.$poi["Longitude"].', 11);';
   echo 'm0 = add_marker(map1, '.$poi["Latitude"].', '.$poi["Longitude"].', "'. $poi_text .'", "fa-cube","green","circle")';
} else {
?>
map1 = load_map ("map1", 45.46, 9.19, 11);
<? } ?>

setTimeout(function () { map1.invalidateSize(); }, 100);

function clearpoi() {
   $("#CodePOI").val(0);
   $("#CodePOItxt").val('');
   // alert ($("#CodePOI").val());
   $("#POI_btns").html('<button class="btn btn-info btn-sm" type="button" onclick="setpoi()">Set POI</button>');
   map1.removeLayer(m0);
   map2.removeLayer(m1);
}

function setpoi() {
   showall_markers();
   myModal.show();   
   setTimeout(function () { map2.invalidateSize(); }, 300);
}

var poisnew = false;
var m1poid = 0;
var to_show = true;

function editpoi() {
   showall_markers();
<? if ($riga["CodePOI"]) { ?>
   m1poid = <?= $riga["CodePOI"] ?>;
   if (typeof m1 == 'undefined') {
      m1 = add_marker(map2, <?= $poi["Latitude"].','.$poi["Longitude"] ?>, "<?= $poi_text ?>", "fa-cube","green","circle", {draggable: true, autoPan:true});
      map2.panTo(new L.LatLng( <?= $poi["Latitude"].','.$poi["Longitude"] ?> ));

      m1.on('dragend', function(e) {
        position = m1.getLatLng();
        alert("Point of interest moved to " + position.lat.toFixed(2) + ', ' + position.lng.toFixed(2) );
      });
   }
<? } ?>
   myModal.show();
   setTimeout(function () { map2.invalidateSize(); }, 300);
}

map2 = load_map ("map2", 45.46, 9.19, 13);

var osmGeocoder = new L.Control.OSMGeocoder();
map2.addControl(osmGeocoder);

function show_mk(objMk) {
   if (objMk.errMsg) console.log(objMk.errMsg);
   // else console.log(objMk.id);
   else {
      //balloon = objMk.name;
      //balloon = objMk.name + "<br/><button type='button' class='btn btn-success btn-sm' onclick='mkSet("+objMk.id+")'>set</button> <button type='button' class='btn btn-warning btn-sm' onclick='mkEdit("+objMk.id+")'>edit</button>"; 
      balloon = objMk.name + "<br/><button type='button' class='btn btn-success btn-sm' onclick='mkSet("+objMk.id+")'>set</button>"; 
      add_marker(map2, objMk.lat, objMk.lng, balloon, "fa-cube","yellow","circle");

      /// aggiungere anche ad un cluster ?
   }
}

function showall_markers() {
   let poilist = <?= $poid_list ?>;
   if (to_show) {
      poilist.forEach(function (e) {
         ajFn("ajax/get_poi",{poiId:e},show_mk);
      });
   to_show = false;
   }
}

function newpoi() {

   var testopoi = $("#PoiTxt").val();
   if (testopoi == '') alert("Please insert the POI name");
   else {

      var centro = map2.getCenter(); // centro.lat, centro.lng
      m1 = add_marker(map2, centro.lat, centro.lng, testopoi, "fa-cube","cyan","circle", {draggable: true, autoPan:true});

      m1.on('dragend', function(e) {
        position = m1.getLatLng();
        alert("Point of interest moved to " + position.lat.toFixed(2) + ', ' + position.lng.toFixed(2) );
      });

      document.getElementById("newpoi_btn").disabled = true;
      ///document.getElementById("delpoi_btn").disabled = false;

      poisnew = true;
   }
}

function delpoi() {

   map2.removeLayer(m1);

   document.getElementById("newpoi_btn").disabled = false;
   document.getElementById("delpoi_btn").disabled = true;
}

function changepoi(poid) {
   // rimuove m1 e lo ricrea con altro colore, quindi assegna il suo id (lo pesca dal pulsantino interno) alla var m1poid

   m1poid = poid;
}

function savepoi() {
   $("#CodePOItxt").val( $("#PoiTxt").val() );
   if (poisnew) {

       var mposition = m1.getLatLng();
       $("#PoiLat").val(mposition.lat);
       $("#PoiLng").val(mposition.lng);

       // visualizzare m0:
       if (typeof m0 != 'undefined') map1.removeLayer(m0);
       m0 = add_marker(map1, mposition.lat, mposition.lng, $("#PoiTxt").val(), "fa-cube","green","circle");
       $("#btnSet").hide();
       ///$("#btnEdit").toggleClass("d-none");
       $("#btnClear").toggleClass("d-none");
       map1.panTo(m0.getLatLng());

       // salvare m1 nella tabella POI:
       do_submit("new_poi", "poiform");     
   }
   else {
      updatepoi(m1poid);
   }

   myModal.hide();
}

function updatepoi(poid) {

   // riceve id del nuovo poi, lo assegna al campo hidden del form
   if (poisnew) $("#CodePOI").val(poid);

   else {

      // aggiornamento POI form:
      var mposition = m1.getLatLng();
      $("#PoiLat").val(mposition.lat);
      $("#PoiLng").val(mposition.lng);
      $("#PoiCode").val(poid);

      // elimina m0 (se esiste) e lo ricrea:
      if (typeof m0 != 'undefined') map1.removeLayer(m0);
      var mposition = m1.getLatLng();
      m0 = add_marker(map1, mposition.lat, mposition.lng, $("#PoiTxt").val(), "fa-cube","green","circle");
      map1.panTo(m0.getLatLng());

      // aggiornare dati m1 nella tabella POI:
      do_submit("save_poi", "poiform");  
   }

   // alert(poid);
   /// infine se occorre aggiorna i pulsantini per evitare incongruenze
}

function mkSet(poid) {
   var r = confirm("Associare il content item a questo POI ?");
   if (r == true) {      
      ajFn("ajax/get_poi",{poiId:poid},assign_mk);
   } 
}

function assign_mk(objMk) {
   if (objMk.errMsg) console.log(objMk.errMsg);
   else {
      console.log(objMk.id);

      $("#CodePOItxt").val(objMk.name);
      $("#CodePOI").val(objMk.id);

      // elimina m0 (se esiste) e lo ricrea:
      if (typeof m0 != 'undefined') map1.removeLayer(m0);
      m0 = add_marker(map1, objMk.lat, objMk.lng, objMk.name, "fa-cube","green","circle");
      map1.panTo(m0.getLatLng());

      // nascondere i pulsantini:
      //$("#btnEdit").toggleClass("d-none");
      //$("#btnClear").toggleClass("d-none");
      $("#btnSet").hide();
      $("#btnEdit").hide();
      
      myModal.hide();
   }
}

function mkEdit(poid) {
/*
      m1 = add_marker(map2, centro.lat, centro.lng, testopoi, "fa-cube","cyan","circle", {draggable: true, autoPan:true});

      m1.on('dragend', function(e) {
        position = m1.getLatLng();
        alert("Point of interest moved to " + position.lat.toFixed(2) + ', ' + position.lng.toFixed(2) );
      });

      document.getElementById("newpoi_btn").disabled = true;
      document.getElementById("delpoi_btn").disabled = false;

*/
}

</script>
