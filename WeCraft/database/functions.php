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
    $sql = "select `id`,`imgExtension`,`image` from `UserImages` where `userId` = ? ORDER BY `id` ASC;";
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
    $sql = "INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`, `percentageResell`) VALUES (NULL, ?, ?, ?, NULL, NULL, ?, ?, ?, CURRENT_TIMESTAMP(), NULL, NULL);";
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
    $sql = "INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`, `percentageResell`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP(), NULL, NULL);";
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
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`, COALESCE(sum(`ExchangeProduct`.`quantity`),0) as quantityFromPatners from `Product` left join `ExchangeProduct` on `Product`.`id` = `ExchangeProduct`.`product` where `Product`.`artisan` = ? group by `Product`.`id` ORDER BY `Product`.`id` DESC;";
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

    //return an array of associative array with id name iconExtension icon price quantity category quantityFromPatners
    return $elements;
  }

  //Get if the product with this id exists or not
  function doesThisProductExists($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as 'doesThisProductExists' from (select * from `Product` where `id` = ?) as t;";
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

    return $elements[0]["doesThisProductExists"];
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

  //update the general info of a product
  function updateGeneralInfoOfAProduct($productId,$insertedName,$insertedDescription,$insertedPrice,$insertedQuantity){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `name` = ?, `description` = ?, `price` = ?, `quantity` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssssi",$insertedName,$insertedDescription,$insertedPrice,$insertedQuantity,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //update the category of a product
  function updateCategoryOfAProduct($productId,$insertedCategory){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `category` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$insertedCategory,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Delete the icon of a product
  function deleteIconOfAProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `icon` = null, `iconExtension` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Change the icon of a product
  function changeIconOfAProduct($productId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `icon` = ?, `iconExtension` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssi",$imgData,$imgExtension,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add an image to a product
  function addImageToAProduct($productId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `ProductImages` (`id`, `productId`, `imgExtension`, `image`) VALUES (NULL, ?, ?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$productId,$imgExtension,$imgData);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get the number of images of this product
  function getNumberImagesOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberImagesOfThisProduct from (select * from `ProductImages` where `productId` = ?) as t;";
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

    return $elements[0]["numberImagesOfThisProduct"];
  }

  //Get the images of this product
  function getImagesOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`imgExtension`,`image` from `ProductImages` where `productId` = ? ORDER BY `id` ASC;";
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

    //return an array of associative arrays with the images of this product (id, imgExtension, image)
    return $elements;
  }

  //Remove an image to its product specifying the id of the image (and also the productId for security reason)
  function removeThisImageToAProduct($productId,$imageId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ProductImages` WHERE `productId` = ? and `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$imageId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add a tag to a product
  function addATagToAProduct($productId,$tag){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `ProductTags` (`id`, `productId`, `tag`) VALUES (NULL, ?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("is",$productId,$tag);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get the number of tags of this product
  function getNumberTagsOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberTagsOfThisProduct from (select * from `ProductTags` where `productId` = ?) as t;";
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

    return $elements[0]["numberTagsOfThisProduct"];
  }

  //Get the tags of this product
  function getTagsOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`tag` from `ProductTags` where `productId` = ? ORDER BY `id` ASC;";
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

    //return an array of associative arrays with the tags of this product (id, tag)
    return $elements;
  }

  //Remove a tag to its product specifying the id of the tag (and also the productId for security reason)
  function removeThisTagToAProduct($productId,$tagId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ProductTags` WHERE `productId` = ? and `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$tagId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain infos of a designer (similar to obtainUserInfos but watching Designer table)
  function obtainDesignerInfos($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`description` from `Designer` where `id` = ?;";
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

    //return an associative array with the infos of this designer
    return $elements[0];
  }

  //Get the quantity of this product in the shopping cart by this user
  //returns an array of elements, one elements for each artisan where the product is available (the first element is the owner)
  //each of this element contains an array of: artisanId shopName maxQuantityAvailable quantityInShoppingCart
  function getQuantityOfThisProductInShoppingCartByThisUser($productId,$userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "(select `Product`.`artisan` as artisanId,`Artisan`.`shopName` as shopName,`Product`.`quantity` as maxQuantityAvailable,COALESCE(t.`quantity`,0) as quantityInShoppingCart from (`Product` join `Artisan` on `Product`.`artisan` = `Artisan`.`id`) left join (select `ShoppingCart`.`product`,`ShoppingCart`.`quantity` from `ShoppingCart` where `ShoppingCart`.`customer` = ? and `ShoppingCart`.`artisan` in (select `Product`.`artisan` from `Product` where `Product`.`id` = ?)) as t on `Product`.`id` = t.`product` where `Product`.`id` = ?) union (select `ExchangeProduct`.`artisan` as artisanId,`Artisan`.`shopName` as shopName,`ExchangeProduct`.`quantity` as maxQuantityAvailable,COALESCE(t.`quantity`,0) as quantityInShoppingCart from (`ExchangeProduct` join `Artisan` on `ExchangeProduct`.`artisan` = `Artisan`.`id`) left join (select `ShoppingCart`.`artisan`,`ShoppingCart`.`quantity` from `ShoppingCart` where `ShoppingCart`.`customer` = ? and `ShoppingCart`.`product` = ?) as t on `ExchangeProduct`.`artisan` = t.`artisan` where `ExchangeProduct`.`product` = ? order by `ExchangeProduct`.`artisan` DESC);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiiiii",$userId,$productId,$productId,$userId,$productId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements;
  }

  //Get the general quantity (summing the quantity from specifics artisans who sell this product) of this product in the shopping cart by this user
  function getGeneralQuantityOfThisProductInShoppingCartByThisUser($productId,$userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select COALESCE(sum(`quantity`),0) as quantity from `ShoppingCart` where `product` = ? and `customer` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["quantity"];
  }

  //Update the quantity of this product in shopping cart for this user (witch is a customer)
  //(update or most frequently insert into if the record is not present)
  //(in case the record is present but the quantity is 0, remove the record)
  //Specify also the artisan where you buy the product
  function updateQuantityOfThisProductInShoppingCartForThisUser($productId,$quantity,$userId,$artisanId){
    if(getIfRecordQuantityOfThisProductInShoppingCartByThisUser($productId,$userId,$artisanId)){
      if($quantity > 0){
        updateRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$quantity,$userId,$artisanId);
      } else {
        deleteRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$userId,$artisanId);
      }
    } else {
      if($quantity > 0){
        newRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$quantity,$userId,$artisanId);
      }
    }
  }

  //Update the quantity of this product in shopping cart for this user (witch is a customer) (update the record)
  function updateRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$quantity,$userId,$artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `ShoppingCart` set `quantity` = ? where `product` = ? and `customer` = ? and `artisan` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$quantity,$productId,$userId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Update the quantity of this product in shopping cart for this user (witch is a customer) (insert into this new record)
  function newRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$quantity,$userId,$artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `ShoppingCart` (`customer`,`product`,`artisan`,`quantity`) VALUES (?,?,?,?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$userId,$productId,$artisanId,$quantity);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get if there is a record about the quantity of this product in the shopping cart by this user
  function getIfRecordQuantityOfThisProductInShoppingCartByThisUser($productId,$userId,$artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as ifRecordQuantityOfThisProductInShoppingCartByThisUser from (select * from `ShoppingCart` where `product` = ? and `customer` = ? and `artisan` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iii",$productId,$userId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["ifRecordQuantityOfThisProductInShoppingCartByThisUser"];
  }

  //delete the record about the quantity of this product in shopping cart for this user (witch is a customer)
  function deleteRecordQuantityOfThisProductInShoppingCartForThisUser($productId,$userId,$artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ShoppingCart` WHERE `product` = ? and `customer` = ? and `artisan` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iii",$productId,$userId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get the number of items in the shopping cart of this user (witch is a customer)
  function numberOfItemsInTheShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select COALESCE((select sum(`quantity`) as r from (select * from `ShoppingCart` where `customer` = ?) as t),0) as numberOfItemsInTheShoppingCartOfThisUser;";
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

    return $elements[0]["numberOfItemsInTheShoppingCartOfThisUser"];
  }

  //Obtain a preview of the shopping cart of this user (witch is a customer)
  function obtainPreviewShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select sc.`quantity`,sc.`product`,`User`.`name`,`User`.`surname`,`Artisan`.`shopName`,`Product`.`name` as 'productName',`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price` from ((((select `ShoppingCart`.`product`,sum(`ShoppingCart`.`quantity`) as quantity from `ShoppingCart` where `ShoppingCart`.`customer` = ? and `ShoppingCart`.`quantity` > 0 group by `ShoppingCart`.`product`) as sc join `Product` on sc.`product` = `Product`.`id`) join `User` on `Product`.`artisan` = `User`.`id`) join `Artisan` on `Product`.`artisan` = `Artisan`.`id`);";
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

    //return an array of associative arrays with the infos:
    // quantity product (=the id of the product) name surname shopName productName iconExtension icon price
    return $elements;
  }

  //Empty the shopping cart of this user (witch is a customer)
  function emptyTheShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ShoppingCart` WHERE `customer` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Show the number of items in the shopping cart of this user (given the user id) witch violates the condition of exceeding the available quantity
  function getNumberOfViolatingItemsQ($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfViolatingItemsQ from ((select `ShoppingCart`.`product` as r from `ShoppingCart` join `Product` on `ShoppingCart`.`product` = `Product`.`id` and `ShoppingCart`.`artisan` = `Product`.`artisan` where `Product`.`quantity` < `ShoppingCart`.`quantity` and `ShoppingCart`.`customer` = ?) union (select `ShoppingCart`.`product` as r from `ShoppingCart` join `ExchangeProduct` on `ShoppingCart`.`product` = `ExchangeProduct`.`product` and `ShoppingCart`.`artisan` = `ExchangeProduct`.`artisan` where `ExchangeProduct`.`quantity` < `ShoppingCart`.`quantity` and `ShoppingCart`.`customer` = ?)) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfViolatingItemsQ"];
  }

  //Total price of the shopping cart of this user (witch is a customer)
  function totalPriceShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT sum(`Product`.`price` * `ShoppingCart`.`quantity`) as 'totalPrice' FROM `ShoppingCart` join `Product` on `ShoppingCart`.`product` = `Product`.`id` WHERE `ShoppingCart`.`customer` = ?;";
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

    return $elements[0]["totalPrice"];
  }

  //The current shopping cart of this user (witch is a customer) is moved in the purchases cronology
  function moveCurrentShoppingCartOfThisUserPurchasesCronology($userId,$address){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql1 = "insert into `PurchasesCronology` (`id`,`customer`,`timestamp`,`address`) VALUES (NULL,?,CURRENT_TIMESTAMP(),?);";
    $sql2 = "insert into `ContentPurchase` (`purchaseId`,`product`,`artisan`,`singleItemCost`,`quantity`) select last_insert_id(),`ShoppingCart`.`product`,`ShoppingCart`.`artisan`,`Product`.`price`,`ShoppingCart`.`quantity` from `ShoppingCart` join `Product` on `ShoppingCart`.`product` = `Product`.`id` where `customer` = ?;";
    if($statement = $connectionDB->prepare($sql1)){
      $statement->bind_param("is",$userId,$address);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql1. " . $connectionDB->error;
    }
    if($statement = $connectionDB->prepare($sql2)){
      $statement->bind_param("i",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql2. " . $connectionDB->error;
    }
  }

  //Get the number of purchases of this user (witch is a customer)
  function numberPurchasesOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberPurchasesOfThisUser from (select * from `PurchasesCronology` where `customer` = ?) as t;";
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

    return $elements[0]["numberPurchasesOfThisUser"];
  }

  //Obtain a preview of purchases of this user (witch is a customer)
  function obtainPreviewPurchasesCronologyOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select t.`id`,t.`timestamp`,t.`address`,totalCost,numberOfProducts,numberOfDifferentProducts FROM (select `PurchasesCronology`.`id`,`PurchasesCronology`.`timestamp`,`PurchasesCronology`.`address`,sum(`ContentPurchase`.`singleItemCost` * `ContentPurchase`.`quantity`) as totalCost, sum(`ContentPurchase`.`quantity`) as numberOfProducts, count(distinct `ContentPurchase`.`product`) as numberOfDifferentProducts from `PurchasesCronology` join `ContentPurchase` on `PurchasesCronology`.`id` = `ContentPurchase`.`purchaseId` WHERE `PurchasesCronology`.`customer` = ? group by `PurchasesCronology`.`id`) as t order by t.`timestamp` DESC;";
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

    //return an array of associative arrays with the infos:
    // id timestamp address totalCost numberOfProducts numberOfDifferentProducts
    return $elements;
  }

  //Get if the purchase of this customer with this id exists or not
  //$userId shouldn't be necessary because we can obtain the $userId from the $purchaseId but it's useful both to semplify the query and to improve the security
  function doesThisPurchaseExists($userId,$purchaseId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as 'doesThisPurchaseExists' from (select * from `PurchasesCronology` where `customer` = ? and `id` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$purchaseId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["doesThisPurchaseExists"];
  }

  //Obtain general infos of a purchase
  //$userId shouldn't be necessary because we can obtain the $userId from the $purchaseId but it's useful both to semplify the query and to improve the security
  function purchaseGeneralInfos($userId,$purchaseId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select t.`timestamp`,t.`address`,totalCost,numberOfProducts,numberOfDifferentProducts FROM (select `PurchasesCronology`.`id`,`PurchasesCronology`.`timestamp`,`PurchasesCronology`.`address`,sum(`ContentPurchase`.`singleItemCost` * `ContentPurchase`.`quantity`) as totalCost, sum(`ContentPurchase`.`quantity`) as numberOfProducts, count(distinct `ContentPurchase`.`product`) as numberOfDifferentProducts from `PurchasesCronology` join `ContentPurchase` on `PurchasesCronology`.`id` = `ContentPurchase`.`purchaseId` WHERE `PurchasesCronology`.`customer` = ? and `PurchasesCronology`.`id` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$purchaseId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an associative array with the general infos of this purchase
    // timestamp address totalCost numberOfProducts numberOfDifferentProducts
    return $elements[0];
  }

  //Obtain a specific purchase
  //$userId shouldn't be necessary because we can obtain the $userId from the $purchaseId but it's useful to improve the security
  function obtainPurchase($userId,$purchaseId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT `product`,`artisan`,`singleItemCost`,`quantity` FROM `ContentPurchase` WHERE `purchaseId` = ? and `purchaseId` in (select `id` from `PurchasesCronology` where `customer` = ?) order by `product` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$purchaseId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative arrays with:
    // product (=the id of the product) artisan (=where you buy the product) singleItemCost quantity
    return $elements;
  }

  //Update data last sell based on selling now content of shopping cart of this user (witch is a customer)
  function updateDataLastSellBasedOnShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `lastSell` = CURRENT_TIMESTAMP() where `id` in (select `product` from `ShoppingCart` where `customer` = ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Update the remaining quantity of a product based on the shopping cart of this user
  function updateRemainingQuantityOfTheProductsBasedOnShoppingCartOfThisUser($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql1 = "update `Product` set `Product`.`quantity` = `Product`.`quantity` - (select `ShoppingCart`.`quantity` from `ShoppingCart` where `ShoppingCart`.`product` = `Product`.`id` and `ShoppingCart`.`customer` = ? and `ShoppingCart`.`artisan` = `Product`.`artisan`) where `Product`.`id` in (select q from (select `ShoppingCart`.`product` as q from `ShoppingCart` join `Product` on `ShoppingCart`.`product` = `Product`.`id` where `ShoppingCart`.`customer` = ? and `ShoppingCart`.`artisan` = `Product`.`artisan`) as t);";
    $sql2 = "update `ExchangeProduct` set `ExchangeProduct`.`quantity` = `ExchangeProduct`.`quantity` - COALESCE((select `ShoppingCart`.`quantity` from `ShoppingCart` where `ShoppingCart`.`product` = `ExchangeProduct`.`product` and `ShoppingCart`.`customer` = ? and `ShoppingCart`.`artisan` = `ExchangeProduct`.`artisan`),0) where `ExchangeProduct`.`product` in (select q from (select `ShoppingCart`.`product` as q from `ShoppingCart` join `ExchangeProduct` on `ShoppingCart`.`product` = `ExchangeProduct`.`product` and `ShoppingCart`.`artisan` = `ExchangeProduct`.`artisan` where `ShoppingCart`.`customer` = ?) as t) and `ExchangeProduct`.`artisan` in (select qq from (select `ShoppingCart`.`artisan` as qq from `ShoppingCart` join `ExchangeProduct` on `ShoppingCart`.`product` = `ExchangeProduct`.`product` and `ShoppingCart`.`artisan` = `ExchangeProduct`.`artisan` where `ShoppingCart`.`customer` = ?) as tt);";
    if($statement = $connectionDB->prepare($sql1)){
      $statement->bind_param("ii",$userId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql1. " . $connectionDB->error;
    }
    if($statement = $connectionDB->prepare($sql2)){
      $statement->bind_param("iii",$userId,$userId,$userId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql2. " . $connectionDB->error;
    }
  }

  //Number of units sold of this product
  function numberOfUnitsSoldOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select COALESCE(sum(`quantity`),0) as numberOfUnitsSoldOfThisProduct from `ContentPurchase` where `product` = ?;";
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

    return $elements[0]["numberOfUnitsSoldOfThisProduct"];
  }

  //Obtain a preview of new products (for the home page)
  //We take the products added in last 14 days limit 100 and quantity > 0
  function obtainProductsPreviewNewProducts(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category`,`added` from `Product` where TIMESTAMPDIFF(SECOND, `added`, CURRENT_TIMESTAMP()) < 1036800 and `quantity` > 0 ORDER BY `id` DESC limit 100;";
    if($statement = $connectionDB->prepare($sql)){
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

  //Obtain a preview of most sold products in last period (for the home page)
  //We take the products wich has sold in last 14 days limit 100 and quantity > 0 ordered by number of sold
  function obtainMostSoldProductsPreviewInLastPeriod(){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`,sum(`ContentPurchase`.`quantity`) as numSells from `Product` join `ContentPurchase` on `Product`.`id` = `ContentPurchase`.`product` where `Product`.`lastSell` is not null and TIMESTAMPDIFF(SECOND, `Product`.`lastSell`, CURRENT_TIMESTAMP()) < 1036800 and `Product`.`quantity` > 0 group by `Product`.`id` ORDER BY numSells DESC limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name iconExtension icon price quantity category numSells
    return $elements;
  }

  //Return if a certain artisan is sponsoring a certain product or not
  function isThisArtisanSponsoringThisProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as 'isThisArtisanSponsoringThisProduct' from (select * from `Advertisement` where `artisan` = ? and `product` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["isThisArtisanSponsoringThisProduct"];
  }

  //Start sponsoring this product
  function startSponsoringThisProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `Advertisement` (`artisan`,`product`) VALUES (?,?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Stop sponsoring this product
  function stopSponsoringThisProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `Advertisement` WHERE `artisan` = ? and `product` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Number of products this artisan is sponsoring (except the products is exchange selling)
  function numberProductsThisArtisanIsSponsoringExceptOnesIsExchangeSelling($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsThisArtisanIsSponsoring from (select * from `Product` where `id` in (select `product` from `Advertisement` where `artisan` = ?) and `id` not in (select `product` from `ExchangeProduct` where `artisan` = ?)) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsThisArtisanIsSponsoring"];
  }

  //Obtain a preview of products this artisan is sponsoring (except the products is exchange selling)
  function obtainProductsPreviewThisArtisanIsSponsoringExceptOnesIsExchangeSelling($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`category` from `Product` where `id` in (select `product` from `Advertisement` where `artisan` = ?) and `id` not in (select `product` from `ExchangeProduct` where `artisan` = ?) ORDER BY `id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name iconExtension icon price category
    return $elements;
  }

  //Obtain a preview of products this artisan is sponsoring
  function obtainProductsPreviewThisArtisanIsSponsoring($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`category` from `Product` where `id` in (select `product` from `Advertisement` where `artisan` = ?) ORDER BY `id` DESC;";
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

    //return an array of associative array with id name iconExtension icon price category
    return $elements;
  }

  //Obtain a preview of products about a suggestion of products of artisans who are sponsoring some of your products
  // show in this preview only products you are not sponsoring and quantity > 0 and limit 100
  function obtainProductsPreviewSuggestionProductsOfArtisansWhoAreSponsoringYourProducts($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `quantity` > 0 and `artisan` in (select `artisan` from `Advertisement` where `product` in (select `id` from `Product` where `artisan` = ?)) and `id` not in (select `product` from `Advertisement` where `artisan` = ?) ORDER BY `id` DESC LIMIT 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$artisanId);
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

  //Obtain a preview of products about a suggestion of products of artisans who you are sponsoring other theim products
  // show in this preview only products you are not sponsoring and quantity > 0 and limit 100
  function obtainProductsPreviewSuggestionProductsOfArtisansWhooseYouAreSponsoringSomeOtherProducts($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `quantity` > 0 and `artisan` in (select `artisan` from `Product` where `id` in (select `product` from `Advertisement` where `artisan` = ?)) and `id` not in (select `product` from `Advertisement` where `artisan` = ?) ORDER BY `id` DESC LIMIT 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$artisanId);
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

  //Obtain a preview of the exchange products availble to the store of this artisan
  function obtainExchangeProductsAvailableToYourStore($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`,`ExchangeProduct`.`quantity` as quantityToThePatner from `Product` join `ExchangeProduct` on `Product`.`id` = `ExchangeProduct`.`product` where `ExchangeProduct`.`artisan` = ? ORDER BY `Product`.`id` DESC;";
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

    //return an array of associative array with id name iconExtension icon price quantity category quantityToThePatner
    return $elements;
  }

  //Obtain a preview of your products witch are sold also by other artisans
  function obtainYourProductsWitchAreSoldAlsoByOtherArtisans($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`,`ExchangeProduct`.`quantity` as quantityToThePatner from `Product` join `ExchangeProduct` on `Product`.`id` = `ExchangeProduct`.`product` where `Product`.`artisan` = ? ORDER BY `Product`.`id` DESC;";
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

    //return an array of associative array with id name iconExtension icon price quantity category quantityToThePatner
    return $elements;
  }

  //Return if a certain artisan is selling this product (witch is of another artisan) on him store
  function isThisArtisanSellingThisExchangeProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as 'isThisArtisanSellingThisExchangeProduct' from (select * from `ExchangeProduct` where `artisan` = ? and `product` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["isThisArtisanSellingThisExchangeProduct"];
  }

  //Obtain the quantity of this exange product
  function obtainQuantityExchangeProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "SELECT `quantity` FROM `ExchangeProduct` WHERE `artisan` = ? and `product` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["quantity"];
  }

  //Update the quantity of an exchange product
  function updateQuantityExchangeProduct($userId,$productId,$quantity){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `ExchangeProduct` set `quantity` = ? where `artisan` = ? and `product` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iii",$quantity,$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Start selling this exchange product
  function startSellingThisExchangeProduct($userId,$productId,$quantity){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `ExchangeProduct` (`artisan`,`product`,`quantity`) VALUES (?,?,?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iii",$userId,$productId,$quantity);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Stop selling this exchange product
  function stopSellingThisExchangeProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ExchangeProduct` WHERE `artisan` = ? and `product` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain number of exchange product available to the store of this artisan
  function obtainNumberExchangeProductsAvailableToYourStore($artisanId){    
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberExchangeProductsAvailableToYourStore from (select * from `ExchangeProduct` where `artisan` = ?) as t;";
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

    return $elements[0]["numberExchangeProductsAvailableToYourStore"];
  }

  //Obtain a preview of other artisans who are sponsoring this product
  //except products witch are sold by the same artisan
  function obtainPreviewOtherArtisansWhoAreSponsoringThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` in (select `Advertisement`.`artisan` from `Advertisement` where `Advertisement`.`product` = ?) and `User`.`id` not in (select `ExchangeProduct`.`artisan` from `ExchangeProduct` where `ExchangeProduct`.`product` = ?) group by `User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //Obtain number of other artisans who are sponsoring this product
  function obtainNumberOtherArtisansWhoAreSponsoringThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOtherArtisansWhoAreSponsoringThisProduct from (select * from `Advertisement` where `product` = ? and `artisan` not in (select `artisan` from `ExchangeProduct` where `product` = ?)) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOtherArtisansWhoAreSponsoringThisProduct"];
  }

  //Obtain a preview of other artisans who are selling this exchange product
  function obtainPreviewOtherArtisansWhoAreSellingThisExchangeProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,`ExchangeProduct`.`quantity` from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) join `ExchangeProduct` on `User`.`id` = `ExchangeProduct`.`artisan` where `ExchangeProduct`.`product` = ?;";
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

    //return an array of associative array with id name surname icon iconExtension shopName quantity
    return $elements;
  }

  //Obtain number of other artisans who are selling this exchange product
  function obtainNumberOtherArtisansWhoAreSellingThisExchangeProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOtherArtisansWhoAreSellingThisExchangeProduct from (select * from `ExchangeProduct` where `product` = ?) as t;";
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

    return $elements[0]["numberOtherArtisansWhoAreSellingThisExchangeProduct"];
  }

  //Obtain a preview of artisans who cound be complementary to this artisan
  //Select artisans with products of the same category of the artisan
  function obtainPreviewArtisansWhoCouldBeComplementaryToThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` in (select `Product`.`artisan` from `Product` where `Product`.`artisan` <> ? and `Product`.`category` in (select `Product`.`category` from `Product` where `Product`.`artisan` = ?)) group by `User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //obtain preview of artisans who are sponsoring some of the products of this artisan (exclude artisans who are sponsoring some of your products)
  function obtainPreviewArtisansWhoAreSponsoringSomeOfTheProductsOfThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Advertisement`.`product`) as numberProductsIsSponsoring from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) join `Advertisement` on `User`.`id` = `Advertisement`.`artisan` where `Advertisement`.`artisan` not in (select `User`.`id` from `User` join `Artisan` on `User`.`id` = `Artisan`.`id` where `User`.`id` <> ? and `User`.`id` in (select `Advertisement`.`artisan` from `Advertisement` join `Product` on `Product`.`id` = `Advertisement`.`product` where `Product`.`artisan` = ?) and `User`.`id` in (select `Product`.`artisan` from `Product` where `Product`.`id` in (select `Advertisement`.`product` from `Advertisement` where `Advertisement`.`artisan` = ?))) and `Advertisement`.`product` in (select `Product`.`id` from `Product` where `Product`.`artisan` = ?) group by `Advertisement`.`artisan` order by numberProductsIsSponsoring DESC,`User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$artisanId,$artisanId,$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberProductsIsSponsoring
    return $elements;
  }

  //obtain preview of artisans whoose this artisan is sponsoring some products (exclude artisans who are sponsoring some of your products)
  function obtainPreviewArtisansWhooseThisArtisanIsSponsoringSomeProducts($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberProductsIsSponsoring from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` not in (select `User`.`id` from `User` join `Artisan` on `User`.`id` = `Artisan`.`id` where `User`.`id` <> ? and `User`.`id` in (select `Advertisement`.`artisan` from `Advertisement` join `Product` on `Product`.`id` = `Advertisement`.`product` where `Product`.`artisan` = ?) and `User`.`id` in (select `Product`.`artisan` from `Product` where `Product`.`id` in (select `Advertisement`.`product` from `Advertisement` where `Advertisement`.`artisan` = ?))) and `Product`.`id` in (select `Advertisement`.`product` from `Advertisement` where `Advertisement`.`artisan` = ?) group by `User`.`id` order by numberProductsIsSponsoring DESC,`User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$artisanId,$artisanId,$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberProductsIsSponsoring
    return $elements;
  }

  //obtain preview of artisans with witch this artisan is sponsoring each other some product
  function obtainPreviewArtisansWithWhichYouAreSponsoringSomeProductsEachOthers($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` <> ? and `User`.`id` in (select `Advertisement`.`artisan` from `Advertisement` join `Product` on `Product`.`id` = `Advertisement`.`product` where `Product`.`artisan` = ?) and `User`.`id` in (select `Product`.`artisan` from `Product` where `Product`.`id` in (select `Advertisement`.`product` from `Advertisement` where `Advertisement`.`artisan` = ?)) group by `User`.`id` order by `User`.`id` limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iii",$artisanId,$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //Obtain a preview of products with copyright check problems whit the product you are going to add
  //Products of another artisan, with same name, with same category and added in last 14 days
  function copyrightCheck($artisanId,$name,$category){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `artisan` <> ? and `name` = ? and `category` = ? and TIMESTAMPDIFF(SECOND, `Product`.`added`, CURRENT_TIMESTAMP()) < 1036800 ORDER BY `id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$artisanId,$name,$category);
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

  //Obtain a preview of results of global search on products
  function obtainGlobalSearchPreviewProducts($search){
    $search = trim($search);
    $search = "%".$search."%";
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "((select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `name` like ? ORDER BY `id` DESC) union (select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `description` like ? ORDER BY `id` DESC) union (select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `id` in (select `productId` from `ProductTags` where `tag` like ?) ORDER BY `id` DESC)) limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("sss",$search,$search,$search);
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

  //Obtain a preview of results of global search on artisans
  function obtainGlobalSearchPreviewArtisans($search){
    $search = trim($search);
    $search = "%".$search."%";
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`email` like ? or `User`.`name` like ? or `User`.`surname` like ? or concat(`User`.`name`,' ',`User`.`surname`) like ? or concat(`User`.`surname`,' ',`User`.`name`) like ? or `Artisan`.`shopName` like ? or `Artisan`.`description` like ? or `Artisan`.`address` like ? or `Artisan`.`phoneNumber` like ? group by `User`.`id` order by `User`.`id` limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("sssssssss",$search,$search,$search,$search,$search,$search,$search,$search,$search);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //Obtain a preview of results of global search on designers
  function obtainGlobalSearchPreviewDesigners($search){
    $search = trim($search);
    $search = "%".$search."%";
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension` from `User` join `Designer` on `User`.`id` = `Designer`.`id` where `User`.`email` like ? or `User`.`name` like ? or `User`.`surname` like ? or concat(`User`.`name`,' ',`User`.`surname`) like ? or concat(`User`.`surname`,' ',`User`.`name`) like ? or `Designer`.`description` like ? order by `User`.`id` limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssssss",$search,$search,$search,$search,$search,$search);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension
    return $elements;
  }

  //Exectue select multiple rows and cols sql
  function executeSql($sqlStatement){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = $sqlStatement;
    if($statement = $connectionDB->prepare($sql)){
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements;
  }

  //Obtain a preview of products with this category
  function obtainPreviewProductsWithThisCategory($category){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`quantity`,`category` from `Product` where `category` = ? ORDER BY `id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$category);
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

  //Obtain a preview of products with this tag (ordered by id desc)
  function obtainProductsPreviewWithThisTag($tag){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`,`Product`.`added` from `Product` where `Product`.`id` in (select `ProductTags`.`productId` from `ProductTags` where `ProductTags`.`tag` = ?) ORDER BY `Product`.`id` DESC limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$tag);
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

  //Obtain a preview of most sold product with this tag (only product sold at least one time)
  function obtainMostSoldProductsWithThisTag($tag){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id`,`Product`.`name`,`Product`.`iconExtension`,`Product`.`icon`,`Product`.`price`,`Product`.`quantity`,`Product`.`category`,sum(`ContentPurchase`.`quantity`) as numSells from `Product` join `ContentPurchase` on `Product`.`id` = `ContentPurchase`.`product` where `Product`.`lastSell` is not null and `Product`.`id` in (select `ProductTags`.`productId` from `ProductTags` where `ProductTags`.`tag` = ?) group by `Product`.`id` ORDER BY numSells DESC limit 100;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$tag);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name iconExtension icon price quantity category numSells
    return $elements;
  }

  //Return if this product is ready to be exchanged or not
  //the product is ready to be exchanged if a percentage resell is set
  function isThisProductReadyToBeExchanged($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as isThisProductReadyToBeExchanged from `Product` where `id` = ? and `percentageResell` is not null;";
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

    return $elements[0]["isThisProductReadyToBeExchanged"];
  }

  //Return the percentage resell of this product
  function percentageResellOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `percentageResell` from `Product` where `id` = ?;";
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

    //return directly the percentageResell
    return $elements[0]["percentageResell"];
  }

  //set the percentage resell of this product
  function setPercentageResellOfThisProduct($percentageResell,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `percentageResell` = ? where `id` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("di",$percentageResell,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //remove the percentage resell of this product
  function removePercentageResellOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Product` set `percentageResell` = NULL where `id` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain a preview of products for which this user is collaborating for a cooperative design
  function obtainProductsPreviewCooperativeDesign($userId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Product`.`id` as productId,`Product`.`name` as productName,`Product`.`iconExtension` as iconExtension,`Product`.`icon` as icon,`Product`.`artisan` as ownerId,`User`.`name` as ownerName,`User`.`surname` as ownerSurname,count(`CooperativeDesignProducts`.`user`) as numberOfCollaborators from (`Product` join `User` on `Product`.`artisan` = `User`.`id`) left join `CooperativeDesignProducts` on `Product`.`id` = `CooperativeDesignProducts`.`product` where `Product`.`id` in (select `CooperativeDesignProducts`.`product` from `CooperativeDesignProducts` where `CooperativeDesignProducts`.`user` = ?) group by `Product`.`id` ORDER BY `Product`.`id` DESC;";
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

    //return an array of associative array with productId productName iconExtension icon ownerId ownerName ownerSurname numberOfCollaborators
    return $elements;
  }

  //Obtain number of collaborators for this product
  function obtainNumberCollaboratorsForThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberCollaboratorsForThisProduct from (select * from `CooperativeDesignProducts` where `product` = ?) as t;";
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

    return $elements[0]["numberCollaboratorsForThisProduct"];
  }

  //Obtain a preview of other artisans (who are not the owner) who have collaborated for the design of this product
  function obtainPreviewArtisansCollaboratorsOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` in (select `CooperativeDesignProducts`.`user` from `CooperativeDesignProducts` where `CooperativeDesignProducts`.`product` = ?) and `User`.`id` not in (select `Product`.`artisan` from `Product` where `Product`.`id` = ?) group by `User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$productId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //Obtain a preview of other designers (who are not the owner) who have collaborated for the design of this product
  function obtainPreviewDesignersCollaboratorsOfThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension` from `User` where `User`.`id` in (select `CooperativeDesignProducts`.`user` from `CooperativeDesignProducts` where `CooperativeDesignProducts`.`product` = ?) and `User`.`id` in (select `Designer`.`id` from `Designer`);";
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

    //return an array of associative array with id name surname icon iconExtension
    return $elements;
  }

  //Number of products for witch this user (artisan or designer) is collaborating for a cooperating design
  //except, in case he is an artisan, its product, products he sells and products he is sponsoring
  function numberOfOtherProductsThisUserIsCollaboratingFor($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberOfOtherProductsThisUserIsCollaboratingFor from (select * from `Product` where `id` in (select `product` from `CooperativeDesignProducts` where `user` = ?) and `id` not in (select `id` from `Product` where `artisan` = ?) and `id` not in (select `product` from `ExchangeProduct` where `artisan` = ?) and `id` not in (select `product` from `Advertisement` where `artisan` = ?)) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$artisanId,$artisanId,$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberOfOtherProductsThisUserIsCollaboratingFor"];
  }

  //Obtain a preview of products for witch this user (artisan or designer) is collaborating for a cooperating design
  //except, in case he is an artisan, its product, products he sells and products he is sponsoring
  function previewOtherProductsThisUserIsCollaboratingFor($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`name`,`iconExtension`,`icon`,`price`,`category` from `Product` where `id` in (select `product` from `CooperativeDesignProducts` where `user` = ?) and `id` not in (select `id` from `Product` where `artisan` = ?) and `id` not in (select `product` from `ExchangeProduct` where `artisan` = ?) and `id` not in (select `product` from `Advertisement` where `artisan` = ?) ORDER BY `id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiii",$artisanId,$artisanId,$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name iconExtension icon price category
    return $elements;
  }

  //Number of products of this artisan whitch are in collaboration for the design (in groups of at least 2)
  function numberProductsOfThisArtisanInCollaboration($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberProductsOfThisArtisanInCollaboration from (select * from `Product` where `id` in (select `product` from `CooperativeDesignProducts` where `user` <> ?) and `artisan` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberProductsOfThisArtisanInCollaboration"];
  }

  //Return if this user is collaborating for the design of this product
  function isThisUserCollaboratingForTheDesignOfThisProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as isThisUserCollaboratingForTheDesignOfThisProduct from (select * from `CooperativeDesignProducts` where `user` = ? and `product` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["isThisUserCollaboratingForTheDesignOfThisProduct"];
  }

  //start the collaboration for the cooperating design for this product
  function startCooperatingDesignForThisProduct($userId,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `CooperativeDesignProducts` (`user`,`product`) VALUES (?,?);";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$userId,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //add the sheet for the cooperating design (when you start the collaboration for the cooperating design for this product)
  function addSheetCooperatingDesignForThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `SheetProducts` (`product`,`content`,`lastUpdateFrom`,`lastUpdateWhen`) VALUES (?,'',NULL,CURRENT_TIMESTAMP());";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get if an user with this email address exists or not
  function doesThisUserGivenEmailExists($emailAddress){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as doesThisUserExists from (select * from `User` where `email` = ? and `emailVerified` = 1) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("s",$emailAddress);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["doesThisUserExists"];
  }

  //delete the collaboration for the cooperating design for this product
  function deleteCooperatingDesignForThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "delete from `CooperativeDesignProducts` WHERE `product` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //delete the sheet of a collaboration for the cooperating design for this product
  function deleteSheetCooperatingDesignForThisProduct($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "delete from `SheetProducts` WHERE `product` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain a preview of this artisan
  function obtainPreviewThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` = ? group by `User`.`id`;";
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

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements[0];
  }

  //Obtain the content of the sheet
  function obtainSheetContent($productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `SheetProducts`.`content`,`SheetProducts`.`lastUpdateWhen`,`SheetProducts`.`lastUpdateFrom`,`User`.`name`,`User`.`surname`,`User`.`email` from `SheetProducts` left join `User` on `SheetProducts`.`lastUpdateFrom` = `User`.`id` where `SheetProducts`.`product` = ?;";
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

    //return an array of associative array with content lastUpdateWhen lastUpdateFrom name surname email
    return $elements[0];
  }

  //Update the sheet (cor the cooperative design)
  function updateSheet($newContent,$lastUpdateFrom,$productId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `SheetProducts` set `content` = ?, `lastUpdateFrom` = ?, `lastUpdateWhen` = CURRENT_TIMESTAMP() where `product` = ?;";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("sii",$newContent,$lastUpdateFrom,$productId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Obtain a preview of projects of this designer not already assigned to an artisan
  function obtainProjectsPreviewOfThisDesignerNotAssigned($designerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `designer` = ? and `Project`.`id` not in (select `ProjectAssignArtisans`.`project` from `ProjectAssignArtisans`) and `Project`.`claimedByThisArtisan` is null order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$designerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects of this designer not assigned to at least an artisan
  function obtainProjectsPreviewOfThisDesignerAssigned($designerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `designer` = ? and `Project`.`id` in (select `ProjectAssignArtisans`.`project` from `ProjectAssignArtisans`) and `Project`.`claimedByThisArtisan` is null order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$designerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects of this designer claimed by an artisan
  function obtainProjectsPreviewOfThisDesignerClaimed($designerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `designer` = ? and `Project`.`claimedByThisArtisan` is not null and `Project`.`confirmedByTheCustomer` = 0 order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$designerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects of this designer confirmed by the customer
  function obtainProjectsPreviewOfThisDesignerConfirmed($designerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `designer` = ? and `Project`.`confirmedByTheCustomer` = 1 and `Project`.`timestampReady` is null order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$designerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects of this designer completed and ready
  function obtainProjectsPreviewOfThisDesignerCompleted($designerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `designer` = ? and `Project`.`timestampReady` is not null order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$designerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects assigned to this artisan
  function obtainProjectsPreviewAssignedToThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`id` in (select `ProjectAssignArtisans`.`project` from `ProjectAssignArtisans` where `ProjectAssignArtisans`.`artisan` = ?) and `Project`.`claimedByThisArtisan` is null order by `Project`.`id` DESC;";
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

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects claimed by this artisan
  function obtainProjectsPreviewClaimedByThisArtisan($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`claimedByThisArtisan` is not null and `Project`.`claimedByThisArtisan` = ? and `Project`.`confirmedByTheCustomer` = 0 order by `Project`.`id` DESC;";
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

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects assigned to this artisan and confirmed
  function obtainProjectsPreviewOfThisArtisanConfirmed($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`claimedByThisArtisan` = ? and `Project`.`confirmedByTheCustomer` = 1 and `Project`.`timestampReady` is null order by `Project`.`id` DESC;";
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

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects completed and ready by this artisan
  function obtainProjectsPreviewOfThisArtisanCompleted($artisanId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`claimedByThisArtisan` = ? and `Project`.`timestampReady` is not null order by `Project`.`id` DESC;";
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

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects not yet claimed and created for this customer
  function obtainProjectsPreviewNotClaimedThisCustomer($customerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`claimedByThisArtisan` is null and `Project`.`customer` = ? order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$customerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of claimed projects and created for this customer
  function obtainProjectsPreviewClaimedThisCustomer($customerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`claimedByThisArtisan` is not null and `Project`.`confirmedByTheCustomer` = 0 and `Project`.`customer` = ? order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$customerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects confirmed by this customer
  function obtainProjectsPreviewOfThisCustomerConfirmed($customerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`confirmedByTheCustomer` = 1 and `Project`.`timestampReady` is null and `Project`.`customer` = ? order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$customerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //Obtain a preview of projects completed and ready for this customer
  function obtainProjectsPreviewOfThisCustomerCompleted($customerId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`timestampReady` is not null and `Project`.`customer` = ? order by `Project`.`id` DESC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$customerId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements;
  }

  //insert on Project a new project (without specifying an icon image)
  function addANewProjectWithoutIcon($designerId,$customerId,$name,$description,$price,$percentageToDesigner){
    //insert on Project
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Project` (`id`, `designer`, `customer`, `name`, `description`, `iconExtension`, `icon`, `price`, `percentageToDesigner`, `claimedByThisArtisan`, `confirmedByTheCustomer`, `timestampPurchase`, `address`, `timestampReady`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL, ?, ?, NULL, 0, NULL, NULL, NULL);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iissdd",$designerId,$customerId,$name,$description,$price,$percentageToDesigner);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //insert on Project a new project (specifying an icon image)
  function addANewProjectWithIcon($designerId,$customerId,$name,$description,$imgExtension,$imgData,$price,$percentageToDesigner){
    //insert on Project
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Project` (`id`, `designer`, `customer`, `name`, `description`, `iconExtension`, `icon`, `price`, `percentageToDesigner`, `claimedByThisArtisan`, `confirmedByTheCustomer`, `timestampPurchase`, `address`, `timestampReady`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, NULL, 0, NULL, NULL, NULL);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iissssdd",$designerId,$customerId,$name,$description,$imgExtension,$imgData,$price,$percentageToDesigner);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Get if the project with this id exists or not
  function doesThisProjectExists($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as 'doesThisProjectExists' from (select * from `Project` where `id` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["doesThisProjectExists"];
  }

  //Obtain a project infos of a project given the id
  function obtainProjectInfos($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `Project`.`id`,`Project`.`designer`,`Project`.`customer`,`Project`.`name`,`Project`.`description`,`Project`.`iconExtension`,`Project`.`icon`,`Project`.`price`,`Project`.`percentageToDesigner`,`Project`.`claimedByThisArtisan`,`Project`.`confirmedByTheCustomer`,`Project`.`timestampPurchase`,`Project`.`address`,`Project`.`timestampReady` from `Project` where `Project`.`id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array with id designer customer name description iconExtension icon price percentageToDesigner claimedByThisArtisan confirmedByTheCustomer timestampPurchase address timestampReady
    return $elements[0];
  }

  //Obtain a preview of the artisans to witch is assigned this project
  function obtainPreviewArtisansToWitchIsAssignedThisProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `User`.`id`,`User`.`name`,`User`.`surname`,`User`.`icon`,`User`.`iconExtension`,`Artisan`.`shopName`,count(`Product`.`id`) as numberOfProductsOfThisArtisan from (`User` join `Artisan` on `User`.`id` = `Artisan`.`id`) left join `Product` on `User`.`id` = `Product`.`artisan` where `User`.`id` in (select `ProjectAssignArtisans`.`artisan` from `ProjectAssignArtisans` where `ProjectAssignArtisans`.`project` = ?) group by `User`.`id`;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative array with id name surname icon iconExtension shopName numberOfProductsOfThisArtisan
    return $elements;
  }

  //Number of artisans who are assigned to this project
  function numberArtisansAssignedThisProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberArtisansAssignedThisProject from (select * from `ProjectAssignArtisans` where `project` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberArtisansAssignedThisProject"];
  }

  //Return if this user can see this project
  //Designer: only the designer creator of the project
  //Customer: only the customer for which is the project
  //Artisans: only the artisan who has claimed the project and, in case the project is not confirmed, also the artisans who are assigned to this project
  //AAAAAAAA add also artisans and designers who are cooperating for the designer of this project
  function doesThisUserCanSeeThisProject($userId,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as doesThisUserCanSeeThisProject from ((select `id` as e from `Project` where `designer` = ? and `id` = ?) union (select `id` as e from `Project` where `customer` = ? and `id` = ?) union (select `id` as e from `Project` where `claimedByThisArtisan` is not null and `claimedByThisArtisan` = ? and `id` = ?) union (select `ProjectAssignArtisans`.`project` as e from `ProjectAssignArtisans` join `Project` on `ProjectAssignArtisans`.`project` = `Project`.`id` where `ProjectAssignArtisans`.`artisan` = ? and `ProjectAssignArtisans`.`project` = ? and `Project`.`confirmedByTheCustomer` = 0) limit 1) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iiiiiiii",$userId,$projectId,$userId,$projectId,$userId,$projectId,$userId,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["doesThisUserCanSeeThisProject"];
  }

  //Get the number of images of this project
  function getNumberImagesOfThisProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as numberImagesOfThisProject from (select * from `ProjectImages` where `projectId` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["numberImagesOfThisProject"];
  }

  //Get the images of this project
  function getImagesOfThisProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select `id`,`imgExtension`,`image` from `ProjectImages` where `projectId` = ? ORDER BY `id` ASC;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    //return an array of associative arrays with the images of this project (id, imgExtension, image)
    return $elements;
  }

  //update the general info of a project
  function updateGeneralInfoOfAProject($projectId,$insertedName,$insertedDescription,$insertedPrice,$insertedPercentageToDesigner){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `name` = ?, `description` = ?, `price` = ?, `percentageToDesigner` = ?, `claimedByThisArtisan` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssddi",$insertedName,$insertedDescription,$insertedPrice,$insertedPercentageToDesigner,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Delete the icon of a project
  function deleteIconOfAProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `icon` = null, `iconExtension` = null, `claimedByThisArtisan` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Change the icon of a project
  function changeIconOfAProject($projectId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `icon` = ?, `iconExtension` = ?, `claimedByThisArtisan` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ssi",$imgData,$imgExtension,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Add an image to a project
  function addImageToAProject($projectId,$imgExtension,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `ProjectImages` (`id`, `projectId`, `imgExtension`, `image`) VALUES (NULL, ?, ?, ?);";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("iss",$projectId,$imgExtension,$imgData);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Make this project unclaimed
  function makeThisProjectUnclaimed($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `claimedByThisArtisan` = null where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Remove an image to its project specifying the id of the project (and also the projectId for security reason)
  function removeThisImageToAProject($projectId,$imageId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ProjectImages` WHERE `projectId` = ? and `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$projectId,$imageId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Return if this user (which is an artisan) is assigned to this project
  function isThisArtisanAssignedToThisProject($artisanId,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as isThisArtisanAssignedToThisProject from (select * from `ProjectAssignArtisans` where `artisan` = ? and `project` = ?) as t;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }

    $results = $statement->get_result();
    while($element = $results->fetch_assoc()){
      $elements[] = $element;
    }

    return $elements[0]["isThisArtisanAssignedToThisProject"];
  }

  //Assign this artisan to this project
  function assignArtisanToThisProject($artisanId,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "insert into `ProjectAssignArtisans` (`project`,`artisan`) VALUES (?,?);";
    
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$projectId,$artisanId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Remove this artisan as a candidate for a project
  function removeThisArtisanFromThisProject($artisanId,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "DELETE FROM `ProjectAssignArtisans` WHERE `artisan` = ? and `project` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //This user (which is an artisan assigned to this project) claims this project
  function claimThisProject($artisanId,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `claimedByThisArtisan` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("ii",$artisanId,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Confirm this project sending the order
  function confirmThisProject($address,$projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `confirmedByTheCustomer` = 1, `timestampPurchase` = CURRENT_TIMESTAMP(), `address` = ? where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("si",$address,$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

  //Confirm this project sending the order
  function setReadyThisProject($projectId){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "update `Project` set `timestampReady` = CURRENT_TIMESTAMP() where `id` = ?;";
    if($statement = $connectionDB->prepare($sql)){
      $statement->bind_param("i",$projectId);
      $statement->execute();
    } else {
      echo "Error not possible execute the query: $sql. " . $connectionDB->error;
    }
  }

?>
