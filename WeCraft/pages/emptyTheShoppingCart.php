<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Empty the shopping cart
  //This page is visible only to customers
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse == "Customer"){
    $numberOfItemsInTheShoppingCartOfThisUser = numberOfItemsInTheShoppingCartOfThisUser($_SESSION["userId"]);
    if($numberOfItemsInTheShoppingCartOfThisUser > 0){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        upperPartOfThePage(translate("Shopping cart"),"");
        //Receive post request to empty the shopping cart
        $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
        //Check on the input form data
        if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
          addParagraph(translate("Error of the csrf token"));
        } else {
          //Empty the shopping cart
          emptyTheShoppingCartOfThisUser($_SESSION["userId"]);
          addParagraph(translate("Done"));
        }
      } else {
        upperPartOfThePage(translate("Shopping cart"),"./shoppingCart.php");
        addParagraph(translate("Empty the shopping cart")."?");
        //Form to insert data to empty the shopping cart
        startForm1();
        startForm2($_SERVER['PHP_SELF']);
        endForm(translate("Empty the shopping cart"));
      }
    } else {
      upperPartOfThePage(translate("Shopping cart"),"");
      addParagraph(translate("You have no item in the shopping cart"));
    }
  } else {
    upperPartOfThePage(translate("Shopping cart"),"");
    addParagraph(translate("This page is visible only to customers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
