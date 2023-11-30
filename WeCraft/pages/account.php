<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //This page is to do the login or to create a new account or event to continue as guest
  doInitialScripts();
  upperPartOfThePage(translate("Account"),"./index.php");
  //if you are logged in, you are redirect to your starting page (according if you are a customer or an artisan or a designer)
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  switch($kindOfTheAccountInUse){
    case "Guest":
      break;
    case "Customer":
      header('Location: ./map.php');
      break;
    case "Artisan":
      header('Location: ./artisan.php');
      break;
    case "Designer":
      header('Location: ./AAAAALOCATIONQUANDOADESIGNER.php');
      break;
    default:
      header('Location: ./logout.php');
  }
  $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
  ?>
    <!-- Title Welcome to WeCraft -->
    <?php addTitle(translate("Welcome to WeCraft")); ?>
    <!-- Log in -->
    <div class="row mb-3">
      <p><?= translate("Log in:") ?></p>
      <form method="post" action="./doLogin.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="insertedEmail" class="form-label"><?= translate("Email address") ?></label>
          <input class="form-control" id="insertedEmail" aria-describedby="emailHelp" type="text" name="insertedEmail" maxlength="49">
        </div>
        <div class="mb-3">
          <label for="insertedPassword" class="form-label"><?= translate("Password") ?></label>
          <input type="password" class="form-control" id="insertedPassword" type="text" name="insertedPassword">
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
        <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
      </form>
    </div>
    <!-- Alternative options to log in -->
    <?php
      addParagraphWithoutMb3(translate("...or..."));
      addButtonLink(translate("Continue as guest"),"./map.php");
      addParagraphWithoutMb3(translate("...or..."));
      addParagraphWithoutMb3(translate("Create an account:"));
      addButtonLink(translate("New customer"),"./createNewAccountAsCustomer.php");
      addButtonLink(translate("New artisan"),"./createNewAccountAsArtisan.php");
      addButtonLink(translate("New designer"),"./createNewAccountAsDesigner.php");
      lowerPartOfThePage([]);
    ?>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const insertedEmail = document.getElementById('insertedEmail');
        const insertedPassword = document.getElementById('insertedPassword');

        function isValidEmail(email) {
          const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          return emailRegex.test(email);
        }

        //prevent sending form with errors
        form.onsubmit = function(e){
          if(insertedEmail.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the email address") ?>");
          } else if(!isValidEmail(insertedEmail.value)){
            e.preventDefault();
            alert("<?= translate("Email address not valid") ?>");
          } else if(insertedPassword.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the password") ?>");
          }
        }
      </script>
    <?php
      include "./../database/closeConnectionDB.php";
?>
