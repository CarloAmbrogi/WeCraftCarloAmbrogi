<?php

echo "<!-- role: ".$userinfo["ruolo"]." -->";

?>


<div class="container" >
<?= $contenuto ?>
</div>




<!-- jquery UI (CDN) (required ??) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- custom js -->
<script src="http://carloambrogipolimi.altervista.org/Magis/js/navigate.js"></script> 
<script src="http://carloambrogipolimi.altervista.org/Magis/js/forms.js"></script>

<script>

var jsroot = "<?= $BASE_URL ?>";   

/// do_geolocate();

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

