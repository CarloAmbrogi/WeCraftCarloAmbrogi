<?php
  //Useful functions with the database

  //Add a new account for a new customer
  function addANewCustomer($connectionDB,$email,$passwordHash,$name,$surname){
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

?>
