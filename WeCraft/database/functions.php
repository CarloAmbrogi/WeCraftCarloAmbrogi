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

  //Add a new account for a new designer (specifying an icon)
  function addANewDesignerWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode,$description){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
    insertIntoDesigner($email,$description);
  }

  //insert on Designer table (immediatly after you add a designer in the User table)
  function insertIntoDesigner($email,$description){
    //insert on Designer
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Designer` (`id`,`description`) VALUES (?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account as designer to the database
      $statement->bind_param("is",idUserWithThisEmail($email),$description);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add a new account for a new artisan (without specifying an icon image)
  function addANewArtisanWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode,$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithoutIcon($email,$passwordHash,$name,$surname,$verificationCode);
    insertIntoArtisan($email,$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address);
  }

  //Add a new account for a new artisan (specifying an icon)
  function addANewArtisanWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode,$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address){
    deleteThisNonVerifiedAccountIfExists($email);
    insertIntoUserWithIcon($email,$passwordHash,$name,$surname,$imgExtension,$imgData,$verificationCode);
    insertIntoArtisan($email,$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address);
  }

  //insert on Artisan table (immediatly after you add an artisan in the User table)
  function insertIntoArtisan($email,$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address){
    //insert on Artisan
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Artisan` (`id`,`shopName`,`openingHours`,`description`,`phoneNumber`,`latitude`,`longitude`,`address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account as artisan to the database
      $statement->bind_param("isssssss",idUserWithThisEmail($email),$shopName,$openingHours,$description,$phoneNumber,$latitude,$longitude,$address);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
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

  //Get the kind of this account (specifying the userId)
  //Returns "Customer" or "Artisan" or "Designer"
  function getKindOfThisAccount($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT `kindOfThisAccount` from ((SELECT `id`,'Designer' as 'kindOfThisAccount' FROM `Designer`) UNION (SELECT `id`,'Artisan' as 'kindOfThisAccount' FROM `Artisan`) UNION (SELECT `id`,'Customer' as 'kindOfThisAccount' FROM `Customer`)) as t WHERE `id` = ? LIMIT 1;";
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

    if(!is_countable($elements) || count($elements) == 0){
      return "error";
    }
    return $elements[0]["kindOfThisAccount"];
  }

  //verify if the login is valid (returns true or false)
  //you have to insert the email and to match the passord; moreover the account need to be with the email verified
  function isPasswordValid($email,$password){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT `password` FROM `User` WHERE `email` = ? AND `emailVerified` = true;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$email);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    if(is_countable($elements) && count($elements) == 0){
      return false;
    }
    $passwordHashInDatabase = $elements[0]["password"];
    return password_verify($password, $passwordHashInDatabase);
  }

  //update name and surname of an user
  function updateNameAndSurnameOfAnUser($userId,$name,$surname){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `User` set `name` = ?, `surname` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssi",$name,$surname,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Delete the icon of an user
  function deleteIconOfAnUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `User` set `icon` = null, `iconExtension` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Change the icon of an user
  function changeIconOfAnUser($userId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `User` set `icon` = ?, `iconExtension` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssi",$imgData,$imgExtension,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //verify if the passwor of this user id is valid (it's useful to ask for the password for a last time before changing it)
  function isPasswordValidUserId($userId,$password){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT `password` FROM `User` WHERE `id` = ?;";
    
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

    if(is_countable($elements) && count($elements) == 0){
      return false;
    }
    $passwordHashInDatabase = $elements[0]["password"];
    return password_verify($password, $passwordHashInDatabase);
  }

  //Update the password of an user
  function updatePasswordOfAnUser($userId,$passwordHash){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `User` set `password` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$passwordHash,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add an image to an user
  //You should use this function only for aritisans and designers (customers can't add other images)
  function addImageToAnUser($userId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `UserImages` (`id`, `userId`, `imgExtension`, `image`) VALUES (NULL, ?, ?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$userId,$imgExtension,$imgData);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Remove an image to its user specifying the id of the image (and also the userId for security reason)
  function removeThisImageToAnUser($userId,$imageId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `UserImages` WHERE `userId` = ? and `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$imageId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the description of a designer
  function updateDescriptionOfADesigner($userId,$description){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Designer` set `description` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$description,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the description of an artisan
  function updateDescriptionOfAnArtisan($userId,$description){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Artisan` set `description` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$description,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the shop name of an artisan
  function updateShopNameOfAnArtisan($userId,$shopName){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Artisan` set `shopName` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$shopName,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the phone number of an artisan
  function updatePhoneNumberOfAnArtisan($userId,$phoneNumber){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Artisan` set `phoneNumber` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$phoneNumber,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the shop location of an artisan
  function updateShopLocationOfAnArtisan($userId,$insertedLatitude,$insertedLongitude,$insertedAddress){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Artisan` set `latitude` = ?, `longitude` = ?, `address` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("sssi",$insertedLatitude,$insertedLongitude,$insertedAddress,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the opening hours of an artisan
  function updateOpeningHoursOfAnArtisan($userId,$insertedOpeningHours){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Artisan` set `openingHours` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$insertedOpeningHours,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get the number of images of this user
  function getNumberImagesOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberImagesOfThisUser from (select * from `UserImages` where `userId` = ?) as t;";
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

    return $elements[0]["numberImagesOfThisUser"];
  }

  //Get the images of this user
  function getImagesOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`imgExtension`,`image` from `UserImages` where `userId` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative arrays with the images of this user (id, imgExtension, image)
    return $elements;
  }

  //Obtain infos of an artisan (similar to obtainUserInfos but watching Artisan table)
  function obtainArtisanInfos($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`shopName`,`openingHours`,`description`,`phoneNumber`,`latitude`,`longitude`,`address` from `Artisan` where `id` = ?;";
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

    //return an associative array with the infos of this artisan
    return $elements[0];
  }

  //Get the number of products of this artisan
  function getNumberOfProductsOfThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsOfThisArtisan from (select * from `Product` where `artisan` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsOfThisArtisan"];
  }

  //insert on Product a new product (without specifying an icon image)
  function addANewProductWithoutIcon($artisan,$name,$description,$price,$quantity,$category){
    //insert on Product
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`) VALUES (NULL, ?, ?, ?, NULL, NULL, ?, ?, ?, CURRENT_TIMESTAMP(), NULL);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("issdis",$artisan,$name,$description,$price,$quantity,$category);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //insert on Product a new product (specifying an icon image)
  function addANewProductWithIcon($artisan,$name,$description,$imgExtension,$imgData,$price,$quantity,$category){
    //insert on Product
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP(), NULL);";
    if($statement = $connectionDB->prepare($sql)){
      //Add the new account to the database
      $statement->bind_param("issssdis",$artisan,$name,$description,$imgExtension,$imgData,$price,$quantity,$category);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain a preview of products of an artisan
  function obtainProductsPreviewOfThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `artisan` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name iconExtension icon price quantity category
    return $elements;
  }

  //Obtain infos of a product (similar to obtainUserInfos but watching Product table)
  function obtainProductInfos($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`artisan`,`name`,`description`,`iconExtension`,`icon`,`price`,`quantity`,`category`,`added`,`lastSell` from `Product` where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an associative array with the infos of this product
    return $elements[0];
  }

?>
