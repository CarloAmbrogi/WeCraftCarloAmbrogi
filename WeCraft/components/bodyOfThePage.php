<?php

  $L;//global variable to read language strings

  //To create a page you should always:
  //Do initial scripts
  //Second: call upperPartOfThePage
  //Third: Insert the content of the page
  //Fourth: call lowerPartOfThePage

  //This function is to do the initial scripts such as load the strings
  function doInitialScripts(){
    //Start the sessions vars
    session_start();
    //Load the language of the site
    if(isset($_GET["language"])){
      $lang = $_GET["language"];
      setcookie("language", $lang, time() + (86400 * 30), "/");
    }
    $acceptLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if(!isset($_COOKIE['language'])) {
      $lang = $acceptLanguage;
      setcookie("language", $acceptLanguage, time() + (86400 * 30), "/");
    }
    if(!isset($lang) && isset($_COOKIE['language'])){
      $lang = $_COOKIE['language'];
    }
    if(!isset($lang) || $lang != "it"){
      $lang = "en";
    }
    $GLOBALS['$L'] = parse_ini_file(dirname(__FILE__)."/../strings/$lang.ini"); //set global variable to read language strings
  }

  //This function is to load the upper part of the page
  //Parameters: title of the page and link of the back button (empty string if not back button)
  function upperPartOfThePage($titleOfThePage, $backButtonLink)
  {
    //upper part of the page
    ?>
      <!DOCTYPE html>
      <html lang="it">

      <head>
        <meta charset="utf-8">
        <meta name="generator" content="AlterVista - Editor HTML" />
        <meta name="description" content="WeCraft - For artisans in India">
        <!-- Title and icon of the page -->
        <title>
          <?= $titleOfThePage ?>
        </title>
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
        <?php
          if($backButtonLink != ""){
            ?>
              <button type="button" onclick="window.location='<?= $backButtonLink ?>';" class="btn btn-primary"
                style="margin:10px;">⬅️
                <?= translate("Back") ?>
              </button>
            <?php
          } else {
            ?>
              <!-- Little space instead of the back button -->
              <div id="mySpacer"></div>
            <?php
          }
        ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <a class="navbar-brand" href="./index.php"><!-- Image of WeCraft to return to home page -->
            <img src="./Icons/faviconIcon.png" alt="faviconIcon" width="72" height="72"
              class="d-inline-block align-text-center">
          </a>
          <a class="navbar-brand" href="#"
            onclick="window.location.reload(true);"><!-- Title of the page to reload the page -->
            <?= $titleOfThePage ?>
          </a>
          <ul class="navbar-nav me-auto mb-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $GLOBALS['$L']["SLanguage"] ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" onclick="addUrlParameter('language', 'en')">
                    🇬🇧English
                  </a></li>
                <li><a class="dropdown-item" onclick="addUrlParameter('language', 'it')">
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
      <main role="main" class="container" id="spaceAfterNavBar">
    <?php
  }

  //This function is to load the lower part of the page
  //Parameters: array elements for the footer (empty array if no footer);
  //each elements of this array is an array with 2 elements with the title and the link
  function lowerPartOfThePage($elementsForTheFooter){
    ?>
      </main>

        <div class="row" id="finalSpaceBeforeTheFooter"></div>
        <!-- Footer (not all buttons will be showed) -->
        <?php
          if(count($elementsForTheFooter) > 0){
            ?>
              <div class="row">
                <div class="btn-group" role="group" aria-label="Basic outlined example">
                  <?php
                    foreach($elementsForTheFooter as &$elementForTheFooter){
                      ?>
                        <button type="button" class="btn btn-outline-primary" onclick="window.location='<?= $elementForTheFooter[1] ?>';">
                          <?= $elementForTheFooter[0] ?>
                        </button>
                      <?php
                    }
                  ?>
                </div>
              </div>
            <?php
          }
        ?>

      </body>

      </html>

      <script>
        //function to add url parameters (useful to change language)
        function addUrlParameter(name, value) {
          var searchParams = new URLSearchParams(window.location.search)
          searchParams.set(name, value)
          window.location.search = searchParams.toString()
        }
      </script>

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

      #spaceAfterNavBar {
          position: relative;
          top: 150px;
        }

      @media (min-aspect-ratio: 1/1) {
        .btn-group {
          padding-bottom: 0px;
          height: 88px;
        }
        html {
          font-size: 1rem;
        }
        nav {
          height: 90px;
        }
        #spaceAfterNavBar {
          top: 100px;
        }
      }

      #mySpacer {
        width: 10px;
      }
      </style>

      <!-- TODO -->
      <!-- Prevent links in standalone web apps opening Mobile Safari -->

    <?php
  }

?>
