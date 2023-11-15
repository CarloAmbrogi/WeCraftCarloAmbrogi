<?php
  include "../database/access.php";

  //Show if this email address has been used (or not)
  //GET param: thisEmailAddress
  // example of the result:
  // [{"hasThisEmailAddressBeenUsed":1}]

  if(isset($_GET["thisEmailAddress"])){

    $thisEmailAddress = $_GET["thisEmailAddress"];

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

    $encodedData = json_encode($elements, JSON_UNESCAPED_UNICODE);

    print($encodedData);

  }

  include "../database/closeConnectionDB.php";
?>
