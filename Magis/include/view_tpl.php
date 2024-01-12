<?php

echo "<!-- role: ".$userinfo["ruolo"]." -->";

?>

<!-- slider -->
<link href="http://www.mixmap.it/magis/jslibs/nouislider/nouislider.min.css" rel="stylesheet" >

<input type="hidden" id="iniziale" value="0">
<input type="hidden" id="finale" value="0">


<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color:#ffffff;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Navigation Panel</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="border-top">
      Time filter<br/><br/>
      <div id="slider"></div>
      <br/>From: <span id="event-start"></span>
      <br/>To:   <span id="event-end"></span>
    </div>
    <div class="border-top border-bottom mt-3">
      Topic filter<br/><br/>
      <div id="topics"><?= $topictree ?></div>
      <br/>
      <input class='form-check-input me-1' type='checkbox' value='' aria-label='topic item'> include related contents<br>
    </div>
    <button class="btn btn-success mt-3" type="button" onclick="updateMap()" >Update Map</button>
  </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="background-color:#eeeeee;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Content Panel</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div>
      Multimedia contents item list
    </div>
  </div>
</div>


<div class="container" >
<a class="btn btn-info" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Navigation Panel</a>
<!-- a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvasRight" role="button" aria-controls="offcanvasRight">Content Panel</a -->
<!-- a class="btn btn-success" href="javascript:attachLayer(map1,currLayer);" role="button" >Test 1</a -->
<!-- a class="btn btn-success" href="javascript:detachLayer(map1,currLayer);" role="button" >Test 2</a -->
</div>


<div class="container" style="margin-top:20px; min-height:20px;">

<div class="row">
	<div class="col-md-8">
		<div id="map1" class="map-div"></div>
		<div style="font-size:14px; color:#555555; margin-top:10px;">Open the Navigation Panel to select the contents to be displayed</div>
	</div>
	<div class="col-md-4" >
		<div class="card" id="contents"></div>
	</div>
</div>



</div>

<!-- leaflet maps -->
<script src="http://www.mixmap.it/magis/jslibs/leaflet/leaflet.js"></script> 
<script src="http://www.mixmap.it/magis/jslibs/leaflet/plugins/extramarkers/js/leaflet.extra-markers.min.js"></script>
<script src="http://www.mixmap.it/magis/jslibs/leaflet/plugins/geocoder/Control.OSMGeocoder.js"></script>

<!-- jquery UI (CDN) (required ??) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- slider -->
<script src="http://www.mixmap.it/magis/jslibs/nouislider/nouislider.min.js"></script>

<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/maps.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/topictree.js"></script> 

<script>

var jsroot = "<?= $BASE_URL ?>";   

/// do_geolocate();


// generazione del filtro cronologico:

d1 = new Date("2010-01-01").getTime();
d2 = new Date("2022-12-31").getTime();

d01 = new Date("<?= $mindate ?>").getTime();
d02 = new Date("<?= $maxdate ?>").getTime();

var slider = document.getElementById('slider');
/*
noUiSlider.create(slider, {
    start: [20, 80],
    connect: true,
    range: {
        'min': 0,
        'max': 100
    }
});
*/
noUiSlider.create(slider, {
    range: {
        min: d1,
        max: d2
    },
    start: [d01, d02],
    step: 1 * 24 * 60 * 60 * 1000,
    connect: true
});

var dateValues = [
    document.getElementById('event-start'),
    document.getElementById('event-end')
];

var formatter = new Intl.DateTimeFormat('en-GB', {
    dateStyle: 'full'
});

slider.noUiSlider.on('update', function (values, handle) {
    dateValues[handle].innerHTML = formatter.format(new Date(+values[handle]));
    $('#iniziale').val( (new Date(parseInt(values[0]))).toISOString() ); 
    $('#finale').val( (new Date(parseInt(values[1]))).toISOString() );    
});


// caricamento iniziale dei top-nodes:
do_topnodes();


// mappa:

map1 = load_map ("map1", 45.46, 9.19, 14);
setTimeout(function () { map1.invalidateSize(); }, 100);

var osmGeocoder = new L.Control.OSMGeocoder();
map1.addControl(osmGeocoder);


var currLayer = null;
/*
var currLayer = new L.LayerGroup();
attachLayer(map1,currLayer);
showall_mk();
*/


function showone_mk(objMk) {
   if (objMk.errMsg) console.log(objMk.errMsg);
   // else console.log(objMk.id);
   else {
      balloon = objMk.name;
      // balloon = objMk.name + "<br/><button type='button' class='btn btn-success btn-sm' onclick='mkSet("+objMk.id+")'>set</button>";

      ///add_imarker(map1, objMk.lat, objMk.lng, balloon, "fa-cube","green","circle", objMk.id);
      add_imarker_2(map1, currLayer, objMk.lat, objMk.lng, balloon, "fa-cube","green","circle", objMk.id);

      /// aggiungere anche ad un cluster ?
   }
}

function showall_mk() {
   let poilist = <?= $poid_list ?>;
   poilist.forEach(function (e) {
      ajFn("ajax/read_poi",{poiId:e},showone_mk);
   });
}

function onMkClick(e) {
    // alert(this.getLatLng());
    // alert(this.mkid);
    ajFn("ajax/poi_content",{poiId:this.mkid},show_cont);
    /// loadElem ('#contents', page_url = 'ajax/poi_content.php', this.mkid );
}

function show_cont(objResp) {
   if (objResp.errMsg) console.log(objResp.errMsg);
   //else console.log(objResp.htm);
   else $("#contents").html(objResp.htm);
}


</script>

<style>
div.map-div {
 border: 1px solid gray;
 height: 400px;
 width11: 100%;
 max-width11: 1024px;
}
div.accordion-body {background-color:#eeeeee;}
img.itemimg { width:100%; }
li.topicitem:hover {background-color:#ccffaa;}
li.topicitem span {margin-left:10px;}
li.topicitem a {cursor:pointer;}
</style>

