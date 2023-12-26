<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page for adding a partecipant (artisan or designer) as collaborator for the design of this product
  //(get param id is te id of the product related to this collaboration)
  //You need to be the owner of the product
  //You can see this page only if the collaborating design for this product is active
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();

  if($kindOfTheAccountInUse == "Artisan" || $kindOfTheAccountInUse == "Designer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Page with post request
      upperPartOfThePage(translate("Cooperative design"),"cookieBack");
      //Receive post request to add a new partecipant to the cooperative design for this product
      $insertedProductId = $_POST['insertedProductId'];
      $insertedPartecipant = $_POST['insertedPartecipant'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(!doesThisProductExists($insertedProductId)){
        addParagraph(translate("This product doesnt exists"));
      } else if(!isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$insertedProductId)){
        addParagraph(translate("You are not a collaborator for the design of this product"));
      } else if($insertedPartecipant == ""){
        addParagraph(translate("You have missed to insert the new partecipant"));
      } else if(strlen($insertedPartecipant) > 49){
        addParagraph(translate("The email address of the new partecipant is too long"));
      } else if(!isValidEmail($insertedPartecipant)){
        addParagraph(translate("The email address of this new partecipant is not valid"));
      } else {
        //Check to be the owner
        $productInfos = obtainProductInfos($insertedProductId);
        if($_SESSION["userId"] == $productInfos["artisan"]){
          //Check that the user you are going to add exists and it is an artisan or a designer
          //and is not already collaborating for the design of this product
          if(doesThisUserGivenEmailExists($insertedPartecipant)){
            $userToAdd = idUserWithThisEmail($insertedPartecipant);
            $kindUserToAdd = getKindOfThisAccount($userToAdd);
            if($kindUserToAdd == "Artisan" || $kindUserToAdd == "Designer"){
              if(!isThisUserCollaboratingForTheDesignOfThisProduct($userToAdd,$insertedProductId)){
                //The new user is added to the collaboration of this product
                startCooperatingDesignForThisProduct($userToAdd,$insertedProductId);
                $userInfos = obtainUserInfos($userToAdd);
                addParagraph(translate("The user")." ".$userInfos["name"]." ".$userInfos["surname"]." (".$userInfos["email"].") ".translate("has been added"));
              } else {
                addParagraph(translate("The user is already collaborating for the design of this product"));
              }
            } else {
              addParagraph(translate("The user you are going to add is not an artisan or a designer"));
            }
          } else {
            addParagraph(translate("The user you are going to add doesnt exists"));
          }
          addButtonLink(translate("Add another user"),"./addPartecipantsCooperativeDesignProduct.php?id=".urlencode($insertedProductId));
        } else {
          addParagraph(translate("You are not the owner of the product related to this collaboration"));
        }
      }  
    } else {
      //Page without post request
      if(isset($_GET["id"])){
        if(doesThisProductExists($_GET["id"])){
          //Check you are a collaborator
          if(isThisUserCollaboratingForTheDesignOfThisProduct($_SESSION["userId"],$_GET["id"])){
            //Check you are the owner of the related product
            $productInfos = obtainProductInfos($_GET["id"]);
            if($_SESSION["userId"] == $productInfos["artisan"]){
              addScriptAddThisPageToCronology();
              upperPartOfThePage(translate("Cooperative design"),"cookieBack");
              //Real content of the page
              addParagraph(translate("Product").": ".$productInfos["name"]);
              //Form to insert data to add the new partecipant
              startForm1();
              startForm2($_SERVER['PHP_SELF']);
              addShortTextField(translate("Insert the email address of the new partecipant to add for the collaboration for the design of this product"),"insertedPartecipant",49);
              addHiddenField("insertedProductId",$_GET["id"]);
              endForm(translate("Submit"));
              ?>
                <script>
                  //form inserted parameters
                  const form = document.querySelector('form');
                  const insertedPartecipant = document.getElementById('insertedPartecipant');
    
                  function isValidEmail(email){
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return emailRegex.test(email);
                  }
    
                  //prevent sending form with errors
                  form.onsubmit = function(e){
                    if(insertedPartecipant.value === ""){
                      e.preventDefault();
                      alert("<?= translate("You have missed to insert the new partecipant") ?>");
                    } else if(!isValidEmail(insertedPartecipant.value)){
                      e.preventDefault();
                      alert("<?= translate("The email address of this new partecipant is not valid") ?>");
                    }
                  }
                </script>
              <?php
              //End main content of this page
            } else {
              upperPartOfThePage(translate("Error"),"");
              addParagraph(translate("You are not the owner of the product related to this collaboration"));
            }
          } else {
            upperPartOfThePage(translate("Error"),"");
            addParagraph(translate("You are not a collaborator for the design of this product"));
          }
        } else {
          upperPartOfThePage(translate("Error"),"");
          addParagraph(translate("This product doesnt exists"));
        }
      } else {
        upperPartOfThePage(translate("Error"),"");
        //You have missed to specify the get param id of the product
        addParagraph(translate("You have missed to specify the get param id of the product"));
      }
    }
  } else {
    upperPartOfThePage(translate("Error"),"");
    addParagraph(translate("This page is visible only to artisans and designers"));
  }

  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
