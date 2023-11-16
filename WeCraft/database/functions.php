<?php
  //Useful functions with the database

  //Add a new account for a new customer (without specifying an icon image)
  function addANewCustomerWithoutIcon($email,$passwordHash,$name,$surname){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Customer` (`id`, `email`, `password`, `name`, `surname`, `icon`) VALUES (NULL, ?, ?, ?, ?, NULL);";
      if($statement = $connectionDB->prepare($sql)){
        //Add the new account to the database
        $statement->bind_param("ssss",$email,$passwordHash,$name,$surname);
        $statement->execute();
        return;
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }
  }

  //Add a new account for a new customer (without specifying an icon)
  function addANewCustomerWithIcon($email,$passwordHash,$name,$surname,$imgData){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "INSERT INTO `Customer` (`id`, `email`, `password`, `name`, `surname`, `icon`) VALUES (NULL, ?, ?, ?, ?, ?);";
      if($statement = $connectionDB->prepare($sql)){
        //Add the new account to the database
        $statement->bind_param("sssss",$email,$passwordHash,$name,$surname,$imgData);
        $statement->execute();
        return;
      } else {
        echo "Error not possible execute the query: $sql. " . $connectionDB->error;
      }
  }

  //Return if this email address has been used for an account
  function hasThisEmailAddressBeenUsed($thisEmailAddress){
    $connectionDB = $GLOBALS['$connectionDB'];
    $sql = "select count(*) as hasThisEmailAddressBeenUsed from (select * from `Customer` where `email` = ?) as t";
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

?>
