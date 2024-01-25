<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //When you do the login (from the log in page), the form is sent to this page
  doInitialScripts();
  upperPartOfThePage(translate("Log in"),"./index.php");
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for doing the login
    $insertedEmail = $_POST['insertedEmail'];
    $insertedPassword = $_POST['insertedPassword'];
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      if($insertedEmail == "admin" && $insertedPassword == "a"){
        //Log in as profile anlytics administrator
        $_SESSION["userId"] = "admin";
        header('Location: ./anlytics.php');
      } else {
        $isLoginValid = isPasswordValid($insertedEmail, $insertedPassword);
        //$isLoginValid = true;//Uncomment to force log in valid
        if($isLoginValid){
          //log in done
          $_SESSION["userId"] = idUserWithThisEmail($insertedEmail);
          header('Location: ./account.php');
        } else {
          addParagraph(translate("Wrong password or inexistent account or email not yet verified for this account"));
          addButtonLink(translate("Return to home"),"./index.php");
        }
      }
    }
  } else {
    header('Location: ./account.php');
  }
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
?>
