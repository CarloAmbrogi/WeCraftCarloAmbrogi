<?php

$BASE_URL = "http://carloambrogipolimi.altervista.org/Magis/";

$logged = engine_logged();

?>
<!doctype html>
<html lang="it">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MAGIS 0.1</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Separate Popper and Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


<!-- fontawesome icons (CDN) -->
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" />

<!-- maps -->
<link href="http://www.mixmap.it/magis/jslibs/leaflet/leaflet.css" rel="stylesheet" >
<link href="http://www.mixmap.it/magis/jslibs/leaflet/plugins/extramarkers/css/leaflet.extra-markers.min.css" rel="stylesheet" >
<link href="http://www.mixmap.it/magis/jslibs/leaflet/plugins/geocoder/Control.OSMGeocoder.css" rel="stylesheet" >


<!-- google fonts (CDN) -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">

<!-- jquery UI (CDN) (required ??) -->
<!-- link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" / -->


<!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- script src="http://www.mixmap.it/magis/js/jquery-1.10.2.min.js"></script -->
<!-- script src="http://www.mixmap.it/magis/js/jquery-1.9.migrate.js"></script -->

<script src="http://www.mixmap.it/magis/js/jquery.geolocation.min.js"></script> <!-- geolocation plugin -->


  </head>
  <body style="background-color:#aaeeaa;">

  <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4" style="border-bottom:1px solid white;" >

      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <!-- svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg -->
        <span class="fs-4">Magis 1.0</span>
      </a>

      <ul class="nav nav-pills">
<? if ($logged) { ?>
        <li class="nav-item"><a href="<?= $BASE_URL ?>home/list.php" class="nav-link active111" aria-current="page">Contents</a></li>
        <li class="nav-item"><a href="<?= $BASE_URL ?>home/upload.php" class="nav-link">Fetch</a></li>
        <li class="nav-item"><a href="<?= $BASE_URL ?>home/nop.php" class="nav-link">Upload</a></li>
        <li class="nav-item"><a href="<?= $BASE_URL ?>home/view2.php" class="nav-link">View</a></li>
        <li class="nav-item"><a href="<?= $BASE_URL ?>home/logout.php" class="nav-link">Logout</a></li>
<? } else { ?>
        <li class="nav-item"><a href="home.php" class="nav-link active" aria-current="page">Home</a></li>
        <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
<? } ?>
      </ul>

    </header>
  </div>

