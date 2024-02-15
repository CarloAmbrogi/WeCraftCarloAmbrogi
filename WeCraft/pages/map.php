<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Map
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Map"),"");
  addScriptAddThisPageToChronology();
  lowerPartOfThePage(tabBarForTheAccountInUse(),true);
  ?>
    <div id="spaceAfterNavBar2" style="height:100px;">
    </div>
    <div style="overflow: hidden;position: relative; height: calc(100vh - 250px); width:100%">
    <iframe class="" rel="nofollow" style="height: 100%; width:100%; transform: scale(1.0) !important; transform-origin: 0px 0px;" frameborder="0" scrolling="yes" 
    src="http://carloambrogipolimi.altervista.org/Magis/home/viewMapForWeCraft.php" onload="resizeIframe(this)"></iframe></div>
    <script>
      function resizeIframe(obj) {
        //obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
      }
    </script>
  <?php
  include "./../database/closeConnectionDB.php";
?>
