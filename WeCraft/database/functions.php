<?php
  //Useful functions with the database

  //Add a new account for a new customer (without specifying an icon image)
  function addANewCustomerWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode);
    insertIntoCustomer($email);
  }

  //Add a new account for a new customer (specifying an icon)
  function addANewCustomerWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
    insertIntoCustomer($email);
  }

  //insert on User (general data of an user indipendently if it is a customer, artisan or designer) (without specifying an icon image)
  function insertIntoUserWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode){
    //insert on User
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL, false, ?, CURRENT_TIMESTAMP());";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("sssss",$email,$passwordHash,$name,$surname,$verificationCode);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //insert on User (general data of an user indipendently if it is a customer, artisan or designer) (specifying an icon image)
  function insertIntoUserWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`) VALUES (NULL, ?, ?, ?, ?, ?, ?, false, ?, CURRENT_TIMESTAMP());";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("sssssss",$email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //insert on Customer table (immediatly after you add a customer in the User table)
  function insertIntoCustomer($email){
    //insert on Customer
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Customer` (`id`) VALUES (?);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account as customer to the database
      $statement->bind_param("i",idUserWithThisEmail($email));
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add a new account for a new designer (without specifying an icon image)
  function addANewDesignerWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode,$description){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode);
    insertIntoDesigner($email,$description);
  }

  //Add a new account for a new designer (without specifying an icon)
  function addANewDesignerWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode,$description){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
    insertIntoDesigner($email,$description);
  }

  //insert on customer table (immediatly after you add a customer in the User table)
  function insertIntoDesigner($email,$description){
    //insert on Customer
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Designer` (`id`,`description`) VALUES (?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account as customer to the database
      $statement->bind_param("is",idUserWithThisEmail($email),$description);
      $statement->execute();
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
    $sql = "select count(*) as checkVerificationCode from (select * from `User` where `id` = ? and `emailVerified` = false and `verificationCode` = ? and (TIMESTAMPDIFF(SECOND, `timeVerificationCode`, CURRENT_TIMESTAMP()) < 1000)) as t;";
    
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

  //register emailVerified for an user (call this function after checkVerificationCode is ok)
  function registerEmailVerified($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `User` set `emailVerified` = true where `id` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

    //Obtain infos (except password) of an user
    function obtainUserInfos($userId){
      $connectionDB = $GLOBALS['$connectionDB'];
      $sql = "select `id`,`email`,`name`,`surname`,`iconExtension`,`icon`,`emailVerified` from `User` where `id` = ?;";
      if($statement = $connectionDB->prepare($sql)){
        $statement->bind_param("s",$userId);
        $statement->execute();
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }
  
      $results = $statement->get_result();
      while($element = $results->fetch_assoc()){
        $elements[] = $element;
      }
  
      //return an associative array with the infos of this user
      return $elements[0];
    }

?>
