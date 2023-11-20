<?php
  include "./../components/bodyOfThePage.php";
  include "./../functions/costants.php";
  doInitialScripts();

  //Load the language of the site
  if(isset($_GET["language"])){
    $lang = $_GET["language"];
    setcookie("language", $lang, time() + (86400 * 30), "/");
  }
  $acceptLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  if(!isset($_COOKIE['language'])){
    $lang = $acceptLanguage;
    setcookie("language", $acceptLanguage, time() + (86400 * 30), "/");
  }
  if(!isset($lang) && isset($_COOKIE['language'])){
    $lang = $_COOKIE['language'];
  }
  if(!isset($lang) || $lang != "it"){
    $lang = "en";
  }
  $L = parse_ini_file(dirname(__FILE__)."/../strings/$lang.ini");//variable to read language strings
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="generator" content="AlterVista - Editor HTML" />
  <meta name="description" content="WeCraft - For artisans in India">
  <!-- Title and icon of the page -->
  <title><?=$L["Welcome to WeCraft"]?></title>
  <link rel="icon" href="./Icons/faviconIcon.png">
  <!-- WebApp -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="green">
  <meta name="apple-mobile-web-app-title" content="WeCraft">
  <link rel="apple-touch-icon" href="./Icons/faviconIcon.png" sizes="32x32">
</head>

<body>
  <!-- Import jQuery for Bootstrap -->
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <!-- Import Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md fixed-top bg-body-tertiary">
    <!-- Back button (not in all pages it will be present) -->
    <button type="button" onclick="window.location='http://www.example.com';" class="btn btn-primary"
      style="margin:10px;">⬅️<?=$L["Back"]?></button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <a class="navbar-brand" href="./"><!-- Image of WeCraft to return to home page -->
        <img src="<?= WeCraftBaseUrl ?>Icons/faviconIcon.png" alt="faviconIcon" width="72" height="72"
          class="d-inline-block align-text-center">
      </a>
      <a class="navbar-brand" href="#" onclick="window.location.reload(true);"><!-- Title of the page to reload the page -->
        <?=$L["Welcome to WeCraft"]?>
      </a>
      <ul class="navbar-nav me-auto mb-md-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?=$L["SLanguage"]?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?language=en">
                🇬🇧English
              </a></li>
            <li><a class="dropdown-item" href="?language=it">
                🇮🇹Italiano
              </a></li>
          </ul>
        </li>
      </ul>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <!-- Content of the page -->
  <main role="main" class="container" style="position:relative;top:128px;">
      <?php for ($i = 0; $i < 100; $i++) {
        echo "Ciao, sono uno script PHP!<br>";
      } ?>
  </main>

  <div class="row" id="finalSpaceBeforeTheFooter"></div>
  <!-- Footer (not all buttons will be showed) -->
  <div class="row">
    <div class="btn-group" role="group" aria-label="Basic outlined example">
      <button type="button" class="btn btn-outline-primary" onclick="window.location='http://www.example.com';"><?=$L["Map"]?></button>
      <button type="button" class="btn btn-outline-primary" onclick="window.location='http://www.example.com';"><?=$L["Search"]?></button>
      <button type="button" class="btn btn-outline-primary" onclick="window.location='http://www.example.com';"><?=$L["Chats"]?></button>
      <button type="button" class="btn btn-outline-primary" onclick="window.location='http://www.example.com';"><?=$L["More"]?></button>
    </div>
  </div>
</body>
</html>

<style>
  html {
    font-size: 2rem;
  }
  nav {
    height: 128px;
  }
  .dropdown {
    background-color: #F8F9FA;
  }
  .navbar-brand {
    background-color: #F8F9FA;
  }
  .btn-outline-primary {
    background-color: #FFFFFF;
  }
  #finalSpaceBeforeTheFooter {
    height: 250px;
  }
  .btn-group {
    position: fixed;
    bottom: 0px;
    padding: 20px;
    padding-bottom: 40px;
    transform: translateX(24px);
    height: 128px;
  }
  @media (min-aspect-ratio: 1/1) {
    .btn-group {
      padding-bottom: 0px;
      height: 88px;
    }
  }
</style>
