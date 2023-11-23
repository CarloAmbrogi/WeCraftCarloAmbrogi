<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //When you do the login (from the home page), the form is sent to this page
  doInitialScripts();
  upperPartOfThePage(translate("Log in"),"./index.php");
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for doing the login
    $insertedEmail = $_POST['insertedEmail'];
    $insertedPassword = $_POST['insertedPassword'];
    $isLoginValid = isPasswordValid($insertedEmail, $insertedPassword);
    if($isLoginValid){
      //log in done
      $_SESSION["userId"] = idUserWithThisEmail($insertedEmail);
      header('Location: ./index.php');
    } else {
      addParagraph(translate("Wrong password or inexistent account or email not yet verified for this account"));
      addButtonLink(translate("Return to home"),"./index.php");
    }
  } else {
    header('Location: ./index.php');
  }
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
?>
