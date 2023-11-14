<?php

  //Useful functions

  //Check if this email address is valid
  function isValidEmail($email){
    $regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if(!preg_match($regex, $email)){
      return false;
    }
    return true;
  }

?>
