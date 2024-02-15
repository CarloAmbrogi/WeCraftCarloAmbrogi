<?php

  //Here some functions to manage the navigation with the back button

  //Reset chronology
  //for pages you reach directly from the tab
  //via js
  function addScriptResetChronology(){
    ?>
      <script>
        document.cookie = "chronology="+escape("");
        document.cookie = "chronology=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      </script>
    <?php
  }

  //Reset chronology
  //for pages you reach directly from the tab
  //via php
  function resetChronology(){
    if(isset($_COOKIE['chronology'])){
      setcookie("chronology", "", time() + (86400 * 30), "/");
      setcookie("chronology", "", time() - 3600);
    }
  }

  //Add this page to the chronology
  //for pages where you can return back
  function addScriptAddThisPageToChronology(){
    ?>
      <script>
        function getCookie(cname) {
          let name = cname + "=";
          let decodedCookie = decodeURIComponent(document.cookie);
          let ca = decodedCookie.split(';');
          for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }

        let chronologyNow = unescape(getCookie("chronology"));
        let pageNow = window.location.href;
        let langEn = "&language=en";
        let langIt = "&language=it";
        let langLenght = langEn.length;
        if(pageNow.includes(langEn) || pageNow.includes(langIt)){
          pageNow = pageNow.slice(0, -langLenght);
        }

        let chronologyNowArray = chronologyNow.split("{");
        let lastElement = "";
        if(chronologyNowArray.length > 0){
          lastElement = chronologyNowArray[chronologyNowArray.length - 1];
        }
        if(lastElement != pageNow){
          let content = chronologyNow + "{" + pageNow;
          document.cookie = "chronology="+escape(content);
        }
      </script>
    <?php
  }

?>
