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

  //Generate a casual verification code, useful to verify a email in an account
  function generateAVerificationCode(){
    $result = "";
    for($i = 0; $i < 6; $i++){
      $adj = rand(0,9);
      $result = $result.$adj;
    }
    return $result;
  }

  //convert a blob image to a file to incorporate with html
  function blobToFile($imageExtension,$image){
    mkdir(dirname(__FILE__)."/../temp");
    $fileName = hash('sha1', $image).".".$imageExtension;
    $filePath = WeCraftBaseUrl."temp/".$fileName;
    file_put_contents(dirname(__FILE__)."/../temp/".$fileName, $image);
    return $filePath;
  }

?>
