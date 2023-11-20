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
    include(dirname(__FILE__)."/bodyOfThePage/upperPartOfThePage.html");
  }

  //This function is to load the lower part of the page
  //Parameters: array elements for the footer (empty array if no footer);
  //each elements of this array is an array with 2 elements with the title and the link
  function lowerPartOfThePage($elementsForTheFooter){
    include(dirname(__FILE__)."/bodyOfThePage/lowerPartOfThePage.html");
  }

?>
