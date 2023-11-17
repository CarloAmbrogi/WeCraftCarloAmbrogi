<?php
  //Useful functions with the database

  //Add a new account for a new customer (without specifying an icon image)
  function addANewCustomerWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode){
    $connectionDB = $GLOBALS['$connectionDB'];
    deleteThisNonVerifiedAccountIfExists($email);
    $sql = "INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL, false, ?, CURRENT_TIMESTAMP());";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("sssss",$email,$passwordHash,$name,$surname,$verificationCode);
      $statement->execute();
      return;
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add a new account for a new customer (without specifying an icon)
  function addANewCustomerWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode){
    $connectionDB = $GLOBALS['$connectionDB'];
    deleteThisNonVerifiedAccountIfExists($email);
    $sql = "INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`) VALUES (NULL, ?, ?, ?, ?, ?, ?, false, ?, CURRENT_TIMESTAMP());";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("sssssss",$email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
      $statement->execute();
      return;
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Delete this account if it has the mail not verified
  function deleteThisNonVerifiedAccountIfExists($email){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `User` WHERE `email` = ? and `emailVerified` = false;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$email);
      $statement->execute();
      return;
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Return if this email address has been used for an account (if an email address is not verified is as it hasn't been used)
  function hasThisEmailAddressBeenUsed($thisEmailAddress){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as hasThisEmailAddressBeenUsed from (select * from `User` where `email` = ? and `emailVerified` = true) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$thisEmailAddress);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["hasThisEmailAddressBeenUsed"];
  }

  //Give me the id of the user with this email address
  function idUserWithThisEmail($thisEmailAddress){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id` from `User` where `email` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$thisEmailAddress);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["id"];
  }

  //Return if the inserted verification code for te inserted userid is correct or not (if too much time has passed, it's considered wrong)
  function checkVerificationCode($userId,$verificationCode){
    $connectionDB = $GLOBALS['$connectionDB'];
    //Max time to verify the email is 1000 seconds
    $sql = "select count(*) as checkVerificationCode from (select * from `User` where `id` = ? and `emailVerified` = false and `verificationCode` = ? and (TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP(), `timeVerificationCode`) < 1000)) as t;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ss",$userId,$verificationCode);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["checkVerificationCode"];
  }

?>
