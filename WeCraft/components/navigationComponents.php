<?php

  //Here some functions to manage the navigation with the back button

  //Reset cronology
  //for pages you reach directly from the tab
  //via js
  function addScriptResetCronology(){
    ?>
      <script>
        document.cookie = "cronology="+escape("");
        document.cookie = "cronology=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      </script>
    <?php
  }

  //Reset cronology
  //for pages you reach directly from the tab
  //via php
  function resetCronology(){
    if(isset($_COOKIE['cronology'])){
      setcookie("cronology", "", time() + (86400 * 30), "/");
      setcookie("cronology", "", time() - 3600);
    }
  }

  //Add this page to the cronology
  //for pages where you can return back
  function addScriptAddThisPageToCronology(){
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

        let cronologyNow = unescape(getCookie("cronology"));
        let pageNow = window.location.href;
        let langEn = "&language=en";
        let langIt = "&language=it";
        let langLenght = langEn.length;
        if(pageNow.includes(langEn) || pageNow.includes(langIt)){
          pageNow = pageNow.slice(0, -langLenght);
        }

        let cronologyNowArray = cronologyNow.split("{");
        let lastElement = "";
        if(cronologyNowArray.length > 0){
          lastElement = cronologyNowArray[cronologyNowArray.length - 1];
        }
        if(lastElement != pageNow){
          let content = cronologyNow + "{" + pageNow;
          document.cookie = "cronology="+escape(content);
        }
      </script>
    <?php
  }

?>
