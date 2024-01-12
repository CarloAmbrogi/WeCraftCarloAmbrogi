<?php

echo "<!-- role: ".$userinfo["ruolo"]." -->";

?>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color:#ffffff;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Navigation Panel</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div>
      Content navigation elements: layers selection, filters, search box.<br/><br/>
    </div>
      <button class="btn btn-success" type="button">
        Update Map
      </button>
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
<a class="btn btn-info" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Filters Panel</a>
<a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvasRight" role="button" aria-controls="offcanvasRight">Content Panel</a>
<!-- a class="btn btn-success" href="javascript:test01();" role="button" >Test 2</a -->


</div>

<div class="container" style="margin-top:20px; min-height:20px;">
<div id="map1" class="map-div"></div>
</div>




<!-- leaflet maps -->
<script src="http://www.mixmap.it/magis/jslibs/leaflet/leaflet.js"></script> 
<script src="http://www.mixmap.it/magis/jslibs/leaflet/plugins/extramarkers/js/leaflet.extra-markers.min.js"></script> 

<!-- jquery UI (CDN) (required ??) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/maps.js"></script> 

<script>

var jsroot = "<?= $BASE_URL ?>";   

/// do_geolocate();

map1 = load_map ("map1", 45.46, 9.19, 11);
setTimeout(function () { map1.invalidateSize(); }, 100);

showall_mk();



function showone_mk(objMk) {
   if (objMk.errMsg) console.log(objMk.errMsg);
   // else console.log(objMk.id);
   else {
      balloon = objMk.name;
      // balloon = objMk.name + "<br/><button type='button' class='btn btn-success btn-sm' onclick='mkSet("+objMk.id+")'>set</button>"; 
      add_marker(map1, objMk.lat, objMk.lng, balloon, "fa-cube","green","circle");

      /// aggiungere anche ad un cluster ?
   }
}

function showall_mk() {
   let poilist = <?= $poid_list ?>;
   poilist.forEach(function (e) {
      ajFn("ajax/get_poi",{poiId:e},showone_mk);
   });
}


</script>

<style>
div.map-div {
 height: 400px;
 width11: 100%;
 max-width11: 1024px;
 border: 1px solid gray;
 margin11: auto;
}

</style>

