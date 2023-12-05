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
    $kindOfThisAccount = getKindOfThisAccount($_SESSION["userId"]);
    if($kindOfThisAccount == "error"){
      header('Location: '.WeCraftBaseUrl.'pages/logout.php');
    }
    return $kindOfThisAccount;
  }

  //Load the tab bar for the account in use
  function tabBarForTheAccountInUse(){
    switch(getKindOfTheAccountInUse()){
      case "Guest":
        return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
      case "Customer":
        if(numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]) > 0){
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"],[translate("Shopping cart"),"./shoppingCart.php"]];
        } else {
          return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
        }
      case "Artisan":
        return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("My products"),"./artisan.php"],[translate("Cooperate"),"./cooperate.php"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
      case "Designer":
        return [[translate("Map"),"./map.php"],[translate("Search"),"./search.php"],[translate("Personalized items"),"./INSERTLINKAAAAAAAAA"],[translate("Chats"),"./chats.php"],[translate("My WeCraft"),"./myWeCraft.php"]];
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
    return WeCraftBaseUrl."Icons/genericTabBarIcon.png";
  }

  //convert a float number representing a price with 2 digits after the floating point
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

?>
