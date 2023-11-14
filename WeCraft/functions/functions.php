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

  //Check if this email address is valid
  function isValidEmail($email){
    $regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if(!preg_match($regex, $email)){
      return false;
    }
    return true;
  }

?>
