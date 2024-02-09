<?php

  //Useful functions

  //Translate a text as defined in the ini files
  function translate($textToTranslate){
    $result = $GLOBALS['$L'][$textToTranslate];
    if(isset($result) && $result != ""){
      return $result;
    }
    return "MISSEDSTRING".$textToTranslate;
  }

  //Add a translated text from the long text files
  //Ex $name = "myText" -> files in texts folder = myTexten.txt and myTextit.txt
  function text($name){
    $lines = file(dirname(__FILE__)."/../texts/".$name.translate("L").".txt");
    $result = "";
    $addBr = false;
    foreach($lines as &$line){
      if($addBr){
        $result.='<br>';
      }
      $result.=$line;
      $addBr = true;
    }
    return $result;
  }

  //Translate quickly some useful strings
  function translateQuickly($textToTranslate,$langAbbrv){
    switch($textToTranslate){
      case "Artisan":
        if($langAbbrv == "en"){
          return "Artisan";
        }
        if($langAbbrv == "it"){
          return "Artigiano";
        }
      case "No category":
        if($langAbbrv == "en"){
          return "No category";
        }
        if($langAbbrv == "it"){
          return "Nessuna categoria";
        }
      case "Jewerly":
        if($langAbbrv == "en"){
          return "Jewerly";
        }
        if($langAbbrv == "it"){
          return "Gioielleria";
        }
      case "Home decoration":
        if($langAbbrv == "en"){
          return "Home decoration";
        }
        if($langAbbrv == "it"){
          return "Decorazioni per la casa";
        }
      case "Pottery":
        if($langAbbrv == "en"){
          return "Pottery";
        }
        if($langAbbrv == "it"){
          return "Ceramica";
        }
      case "Teppiches":
        if($langAbbrv == "en"){
          return "Teppiches";
        }
        if($langAbbrv == "it"){
          return "Tappeti";
        }
      case "Bedware Bathroomware":
        if($langAbbrv == "en"){
          return "Bedware / Bathroomware";
        }
        if($langAbbrv == "it"){
          return "Coperte / asciugamani";
        }
      case "Artisan craft":
        if($langAbbrv == "en"){
          return "Artisan craft";
        }
        if($langAbbrv == "it"){
          return "Costruzioni artigianali";
        }
      default:
        return "";
    }
  }

  //Check if this email address is valid
  function isValidEmail($email){
    $regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if(!preg_match($regex, $email)){
      return false;
    }
    return true;
  }

  //Check if this phone number is valid
  function isValidPhoneNumber($phoneNumber){
    $regex = "/^(\+?)[0-9\ \( \)]+$/";
    if(!preg_match($regex, $phoneNumber)){
      return false;
    }
    return true;
  }

  //Check if this coordinate is valid
  function isValidCoordinate($coordinate){
    $regex = "/^[0-9]+\.[0-9]+$/";
    if(!preg_match($regex, $coordinate)){
      return false;
    }
    return true;
  }

  //Check if this price is valid
  function isValidPrice($price){
    $regex = "/^[0-9]+\.[0-9][0-9]$/";
    if(!preg_match($regex, $price)){
      return false;
    }
    return true;
  }

  //Check if this is a valid float number
  function isValidFloatNumber($number){
    if(isValidPrice($number)){
      return true;
    }
    $regex = "/^[0-9]+\.?[0-9]*$/";
    if(!preg_match($regex, $number)){
      return false;
    }
    return true;
  }

  function isValidPercentage($number){
    if(isValidFloatNumber($number)){
      return true;
    }
    return isValidQuantity($number);
  }

  //Check if this quantity is valid
  function isValidQuantity($quantity){
    $regex = "/^[0-9]+$/";
    if(!preg_match($regex, $quantity)){
      return false;
    }
    return true;
  }

  //Check if this tag is valid
  function isValidTag($tag){
    $regex = "/^[a-zA-Z0-9]+$/";
    if(!preg_match($regex, $tag)){
      return false;
    }
    return true;
  }

  //Generate a casual verification code, useful to verify a email in an account
  function generateAVerificationCode(){
    $result = "";
    for($i = 0; $i < 6; $i++){
      $adj = rand(0,9);
      $result = $result.$adj;
    }
    return $result;
  }

  //Convert a blob image to a file to incorporate with html
  function blobToFile($imageExtension,$image){
    mkdir(dirname(__FILE__)."/../temp");
    $fileName = hash('sha1', $image).".".$imageExtension;
    $filePath = WeCraftBaseUrl."temp/".$fileName;
    file_put_contents(dirname(__FILE__)."/../temp/".$fileName, $image);
    return $filePath;
  }

  //Returns if this string represents a digit
  function isADigit($c){
    $digits = ["0","1","2","3","4","5","6","7","8","9"];//possible digits
    if(!in_array($c,$digits)){
      return false;
    }
    return true;
  }

  //Returns if this string represents a time
  function isThisStringIsATime($str){
    if(strlen($str) != 5){
      return false;
    }
    $c1 = substr($str,0,1);
    $c2 = substr($str,1,1);
    $c3 = substr($str,2,1);
    $c4 = substr($str,3,1);
    $c5 = substr($str,4,1);
    if($c3 != ":"){
      return false;
    }
    if(!isADigit($c1) || !isADigit($c2) || !isADigit($c4) || !isADigit($c5)){
      return false;
    }
    $first = substr($str,0,2);
    $second = substr($str,3,2);
    if($first >= 24){
      return false;
    }
    if($second >= 60){
      return false;
    }
    return true;
  }

  //Returns if these times are in the correct order
  //if $t = true then is ok if the two times are =
  function correctOrderTimes($a,$b,$t=false){
    $hA = substr($a,0,2);
    $minA = substr($a,3,2);
    $hB = substr($b,0,2);
    $minB = substr($b,3,2);
    if($hA < $hB){
      return true;
    }
    if($hA > $hB){
      return false;
    }
    if($minA < $minB){
      return true;
    }
    if($minA < $minB && $t == true){
      return true;
    }
    return false;
  }

  //Analyze a string representing the opening hours
  //Returns an arrray of 3 elementss
  //element "validity": returns if this string representing the opening hours is valid or not
  //element "nowOpen": returns if this shop is now open or not
  //element "description": returns a text with  a description of the time table
  //Example of a string representing the opening hours: %MonF01:0203:04S05:0607:08
  //max lenght of a string representing the opening hours: 182
  function analyzeStringOpeningHours($stringOpeningHours){
    $resultValidity = true;
    $resultNowOpen = false;
    $resultDescription = "";
    $error = array("validity"=>false, "nowOpen"=>false, "description"=>"error");
    $witchDayIsToday = date("D");
    $hourNow = date('H');
    if(strlen($hourNow) == 1){
      $hourNow = "0".$hourNow;
    }
    $minNow = date('i');
    if(strlen($minNow) == 1){
      $minNow = "0".$minNow;
    }
    $nowTime = $hourNow.":".$minNow;
    $days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];//possible days
    $daysInfo = explode("%", $stringOpeningHours);
    $numberOfThisDayPrev = -1;
    foreach ($daysInfo as &$dayInfo){
      if(strlen($dayInfo) == 14 || strlen($dayInfo) == 25){
        $day = substr($dayInfo,0,3);
        if(!in_array($day,$days)){
          return $error;
        }
        $numberOfThisDay = array_search($day, $days);
        if($numberOfThisDay <= $numberOfThisDayPrev){
          return $error;
        }
        $numberOfThisDayPrev = $numberOfThisDay;
        $resultDescription .= translate($day."L").": ";
        $slot1kind = substr($dayInfo,3,1);
        $slot1info = substr($dayInfo,4,10);
        $slot1infoFrom = substr($slot1info,0,5);
        $slot1infoTo = substr($slot1info,5,5);
        if(!isThisStringIsATime($slot1infoFrom) || !isThisStringIsATime($slot1infoTo)){
          return $error;
        }
        if(!correctOrderTimes($slot1infoFrom,$slot1infoTo)){
          return $error;
        }
        $resultDescription .= translate("from")." ".$slot1infoFrom." ".translate("to")." ".$slot1infoTo;
        $slot2kind = "";
        if($witchDayIsToday == $day){
          if(correctOrderTimes($slot1infoFrom,$nowTime,true) && correctOrderTimes($nowTime,$slot1infoTo,true)){
            $resultNowOpen = true;
          }
        }
        if(strlen($dayInfo) == 25){
          $slot2kind = substr($dayInfo,14,1);
          $slot2info = substr($dayInfo,15,10);
          $slot2infoFrom = substr($slot2info,0,5);
          $slot2infoTo = substr($slot2info,5,5);
          if(!isThisStringIsATime($slot2infoFrom) || !isThisStringIsATime($slot2infoTo)){
            return $error;
          }
          if(!correctOrderTimes($slot2infoFrom,$slot2infoTo)){
            return $error;
          }
          $resultDescription .= " ".translate("and")." ".translate("from")." ".$slot2infoFrom." ".translate("to")." ".$slot2infoTo;
          if(!correctOrderTimes($slot1infoTo,$slot2infoFrom)){
            return $error;
          }
          if($witchDayIsToday == $day){
            if(correctOrderTimes($slot2infoFrom,$nowTime,true) && correctOrderTimes($nowTime,$slot2infoTo,true)){
              $resultNowOpen = true;
            }
          }
        }
        if($slot1kind != "F" && $slot1kind != "S"){
          return $error;
        }
        if($slot2kind != ""){
          if(!($slot1kind == "F" && $slot2kind == "S")){
            return $error;
          }
        }
        $resultDescription .= "%";
      } else {
        if(strlen($dayInfo) != 0){
          return $error;
        }
      }
    }
    if($resultDescription == ""){
      $resultDescription = translate("Never open");
    }
    $result = array("validity"=>$resultValidity, "nowOpen"=>$resultNowOpen, "description"=>$resultDescription);
    return $result;
  }

  //Returns the kind of the account in use: "Guest" or "Customer" or "Artisan" or "Designer"
  function getKindOfTheAccountInUse(){
    if(!isset($_SESSION["userId"])){//guest
      return "Guest";
    }
    if($_SESSION["userId"] == "admin"){
      return "Guest";
    }
    $kindOfThisAccount = getKindOfThisAccount($_SESSION["userId"]);
    if($kindOfThisAccount == "error"){
      header('Location: '.WeCraftBaseUrl.'pages/logout.php');
    }
    return $kindOfThisAccount;
  }

  //Load the tab bar for the account in use
  function tabBarForTheAccountInUse(){
    if($_SESSION["userId"] == "admin"){
      return [[translate("Analytics"),"./analytics.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Historical analytics"),"./historicalAnalytics.php"],[translate("Feedback collaborations"),"./feedbackCollaborations.php"],[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Log out"),"./logout.php"]];
    }
    switch(getKindOfTheAccountInUse()){
      case "Guest":
        return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
      case "Customer":
        if(numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]) > 0){
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"],[translate("Shopping cart"),"./shoppingCart.php"]];
        } else {
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
        }
      case "Artisan":
        if(numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]) > 0){
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("My products"),"./artisan.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Cooperate"),"./cooperate.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"],[translate("Shopping cart"),"./shoppingCart.php"]];
        } else {
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("My products"),"./artisan.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Cooperate"),"./cooperate.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
        }
      case "Designer":
        if(numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]) > 0){
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Cooperate"),"./cooperate.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"],[translate("Shopping cart"),"./shoppingCart.php"]];
        } else {
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Personalized items"),"./personalizedItems.php"],[translate("Cooperate"),"./cooperate.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
        }
      default://error
        return [];
    }
  }

  //Load the corresponding icon for the title of the tab for the tab bar
  function tabBarIconForThisTab($tabTitle){
    if($tabTitle == translate("Map")){
      return WeCraftBaseUrl."Icons/mapIcon.png";
    }
    if($tabTitle == translate("Search")){
      return WeCraftBaseUrl."Icons/searchIcon.png";
    }
    if($tabTitle == translate("My products")){
      return WeCraftBaseUrl."Icons/myProductsIcon.png";
    }
    if($tabTitle == translate("Cooperate")){
      return WeCraftBaseUrl."Icons/cooperateIcon.png";
    }
    if($tabTitle == translate("Chats")){
      return WeCraftBaseUrl."Icons/chatsIcon.png";
    }
    if($tabTitle == translate("My WeCraft")){
      return WeCraftBaseUrl."Icons/menuIcon.png";
    }
    if($tabTitle == translate("Personalized items")){
      return WeCraftBaseUrl."Icons/personalizedProductsIcon.png";
    }
    if($tabTitle == translate("Shopping cart")){
      return WeCraftBaseUrl."Icons/shoppingCartIcon.png";
    }
    if($tabTitle == translate("Analytics")){
      return WeCraftBaseUrl."Icons/analyticsIcon.png";
    }
    if($tabTitle == translate("Historical analytics")){
      return WeCraftBaseUrl."Icons/historicalAnalyticsIcon.png";
    }
    if($tabTitle == translate("Feedback collaborations")){
      return WeCraftBaseUrl."Icons/feedbackCollaborationsIcon.png";
    }
    if($tabTitle == translate("Analytical forecast")){
      return WeCraftBaseUrl."Icons/analyticalForecastIcon.png";
    }
    if($tabTitle == translate("Log out")){
      return WeCraftBaseUrl."Icons/logoutIcon.png";
    }
    return WeCraftBaseUrl."Icons/genericTabBarIcon.png";
  }

  //Convert a float number representing a price with 2 digits after the floating point
  function floatToPrice($price){
    $numbers = explode(".", $price);
    $r1 = $numbers[0];
    $r2 = ".";
    $r3 = "";
    if(count($numbers) > 1){
      $r3 = $numbers[1];
      while(strlen($r3) < 2){
        $r3 = $r3."0";
      }
      while(strlen($r3) > 2){
        $r3 = substr($r3,0,strlen($r3)-1);
      }
    } else {
      $r3 = "00";
    }
    $result = $r1.$r2.$r3;
    return $result;
  }

  //Show the estimated time converting it in days or weeks
  function showEstimatedTime($estimatedTime){
    if($estimatedTime % (60 * 60 * 24 * 7) == 0){
      $r = $estimatedTime / (60 * 60 * 24 * 7);
      if($r == 1){
        return $r." ".translate("week");
      } else {
        return $r." ".translate("weeks");
      }
    }
    if($estimatedTime % (60 * 60 * 24) == 0){
      $r = $estimatedTime / (60 * 60 * 24);
      if($r == 1){
        return $r." ".translate("day");
      } else {
        return $r." ".translate("days");
      }
    }
    return $estimatedTime." ".translate("seconds");
  }

  //Remove the quotes from this string (to prevent sql injection)
  function removeQuotes($str){
    $str = str_replace("'","",$str);
    $str = str_replace("\\","",$str);
    $str = str_replace("\"","",$str);
    $str = str_replace("/","",$str);
    $str = str_replace("|","",$str);
    $str = str_replace("`","",$str);
    return $str;
  }

  //Adjust this text substituting YouTube links with string video clickable ref to the YouTube link
  function adjustTextWithYouTubeLinks($text){
    $text = newlineSpecificStringReplace($text);
    $possiblePrefix1 = "https://youtu.be/";
    $possiblePrefix2 = "https://www.youtube.com/watch?v=";
    $resultArr = [];
    $remainingText = $text;
    while($remainingText != ""){
      $posFound = false;
      $whitchPosFound = 0;
      if($p = strpos(" ".$remainingText,$possiblePrefix1)){
        $p = $p - 1;
        $posFound = true;
        $whitchPosFound = $p;
      }
      if($p = strpos(" ".$remainingText,$possiblePrefix2)){
        $p = $p - 1;
        $youCanUpdatePosition = false;
        if($posFound == true){
          if($p < $whitchPosFound){
            $youCanUpdatePosition = true;
          }
        }
        if($posFound == false){
          $youCanUpdatePosition = true;
        }
        if($youCanUpdatePosition == true){
          $posFound = true;
          $whitchPosFound = $p;
        }
      }
      if($posFound == false){
        array_push($resultArr,$remainingText);
        $remainingText = "";
      } else {
        if($whitchPosFound == 0){
          $endLinkPos = 0;
          if($p = strpos("x".$remainingText," ")){
            $p = $p - 1;
            $endLinkPos = $p;
          }
          if($endLinkPos == 0){
            array_push($resultArr,$remainingText);
            $remainingText = "";
          } else {
            $additiveString = substr($remainingText,0,$endLinkPos);
            array_push($resultArr,$additiveString);
            $remainingText = substr($remainingText,$endLinkPos);
          }
        } else {
          $additiveString = substr($remainingText,0,$whitchPosFound);
          array_push($resultArr,$additiveString);
          $remainingText = substr($remainingText,$whitchPosFound);
        }
      }
    }
    $result = "";
    foreach($resultArr as &$value){
      if($possiblePrefix1 == substr($value,0,strlen($possiblePrefix1)) || $possiblePrefix2 == substr($value,0,strlen($possiblePrefix2))){
        $result.='<a href="'.htmlspecialchars($value).'">'.htmlentities("[").translate("video").htmlentities("]").'</a>';
      } else {
        $result.=htmlentities($value);
      }
    }
    return replaceSpecificStringNewLine($result);
  }

  //Adjust this text substituting links with clickable ref to the link
  function adjustTextWithLinks($text){
    $text = newlineSpecificStringReplace($text);
    $possiblePrefix1 = "https://";
    $possiblePrefix2 = "http://";
    $resultArr = [];
    $remainingText = $text;
    while($remainingText != ""){
      $posFound = false;
      $whitchPosFound = 0;
      if($p = strpos(" ".$remainingText,$possiblePrefix1)){
        $p = $p - 1;
        $posFound = true;
        $whitchPosFound = $p;
      }
      if($p = strpos(" ".$remainingText,$possiblePrefix2)){
        $p = $p - 1;
        $youCanUpdatePosition = false;
        if($posFound == true){
          if($p < $whitchPosFound){
            $youCanUpdatePosition = true;
          }
        }
        if($posFound == false){
          $youCanUpdatePosition = true;
        }
        if($youCanUpdatePosition == true){
          $posFound = true;
          $whitchPosFound = $p;
        }
      }
      if($posFound == false){
        array_push($resultArr,$remainingText);
        $remainingText = "";
      } else {
        if($whitchPosFound == 0){
          $endLinkPos = 0;
          if($p = strpos("x".$remainingText," ")){
            $p = $p - 1;
            $endLinkPos = $p;
          }
          if($endLinkPos == 0){
            array_push($resultArr,$remainingText);
            $remainingText = "";
          } else {
            $additiveString = substr($remainingText,0,$endLinkPos);
            array_push($resultArr,$additiveString);
            $remainingText = substr($remainingText,$endLinkPos);
          }
        } else {
          $additiveString = substr($remainingText,0,$whitchPosFound);
          array_push($resultArr,$additiveString);
          $remainingText = substr($remainingText,$whitchPosFound);
        }
      }
    }
    $result = "";
    foreach($resultArr as &$value){
      if($possiblePrefix1 == substr($value,0,strlen($possiblePrefix1)) || $possiblePrefix2 == substr($value,0,strlen($possiblePrefix2))){
        $result.='<a href="'.htmlspecialchars($value).'">'.htmlentities("[").htmlentities($value).htmlentities("]").'</a>';
      } else {
        $result.=htmlentities($value);
      }
    }
    return replaceSpecificStringNewLine($result);
  }

  //Replace newline characeter in the string with \n
  function newlineForJs($str){
    $newline = "
";
    $strings = explode($newline,$str);
    $result = "";
    $firstTime = true;
    foreach($strings as &$value){
      if($firstTime == false){
        $result.="\\n";
      }
      $result.=$value;
      $firstTime = false;
    }
    $result = str_replace("\r", "", $result);
    $result = str_replace("\n", "", $result);
    return $result;
  }

  //Replace newline characeter in the string with br and fix html special characters
  function newlineForPhpSafe($str){
    $newline = "
";
    $strings = explode($newline,$str);
    $result = "";
    $firstTime = true;
    foreach($strings as &$value){
      if($firstTime == false){
        $result.="<br>";
      }
      $result.=htmlentities($value);
      $firstTime = false;
    }
    $result = str_replace("\r", "", $result);
    $result = str_replace("\n", "", $result);
    return $result;
  }

  //Replace newline characeter in the string with a specific string of characters
  function newlineSpecificStringReplace($str){
    $newline = "
";
    $strings = explode($newline,$str);
    $result = "";
    $firstTime = true;
    foreach($strings as &$value){
      if($firstTime == false){
        $result.=" ahreibeuwfbefbnFIERFBYRYNDSJSHnidebfebn ";
      }
      $result.=$value;
      $firstTime = false;
    }
    $result = str_replace("\r", "", $result);
    $result = str_replace("\n", "", $result);
    return $result;
  }

  //Replace a specific string of characters with br
  function replaceSpecificStringNewLine($str){
    return str_replace(" ahreibeuwfbefbnFIERFBYRYNDSJSHnidebfebn ","<br>",$str);
  }

  //Do a get request to a website
  function doGetRequest($url,$part = 1){
    $emulationConnection = true;//should be false (else emulation via client js)
    if($emulationConnection == false){
      $urlForCurl = $url;
      $curlSES=curl_init();
      curl_setopt($curlSES,CURLOPT_URL,$urlForCurl);
      curl_setopt($curlSES,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curlSES,CURLOPT_HEADER, false);
      $result=curl_exec($curlSES);
      curl_close($curlSES);
      return $result;
    } else {
      ?>
        <script>
          let requestUrl<?= $part ?> = "<?= $url ?>";
          let request<?= $part ?> = new XMLHttpRequest();
          request<?= $part ?>.open("GET", requestUrl<?= $part ?>);
          //request.responseType = "json";//not json but empty file
          request<?= $part ?>.send();
          request<?= $part ?>.onload = function(){
          }
        </script>
      <?php
    }
  }

?>
