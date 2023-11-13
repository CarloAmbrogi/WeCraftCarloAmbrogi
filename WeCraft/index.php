<?php
  include "components/bodyOfThePage.php";

  //Home page of WeCraft
  doInitialScripts();
  upperPartOfThePage($GLOBALS['$L']["Welcome to WeCraft"],"");
  ?>
    <div class="row mb-3">
      <p><?= $GLOBALS['$L']["Welcome to WeCraft"] ?></p>
    </div>
    <div class="row mb-3">
      <p><?= $GLOBALS['$L']["Log in:"] ?></p>
      <form>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label"><?= $GLOBALS['$L']["Email address"] ?></label>
          <input class="form-control" id="insertedEmal" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label"><?= $GLOBALS['$L']["Password"] ?></label>
          <input type="password" class="form-control" id="insertedPassword">
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= $GLOBALS['$L']["Submit"] ?></button>
      </form>
    </div>
    <div class="row">
      <p><?= $GLOBALS['$L']["...or..."] ?></p>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='LINKTOCONTINUEASGUEST';" class="btn btn-primary"
        style="margin:10px;">
        <?= $GLOBALS['$L']["Continue as guest"] ?>
      </button>
    </div>
    <div class="row">
      <p><?= $GLOBALS['$L']["...or..."] ?></p>
    </div>
    <div class="row">
      <p><?= $GLOBALS['$L']["Create an account:"] ?></p>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='./createNewAccountAsCustomer.php';" class="btn btn-primary"
        style="margin:10px;">
        <?= $GLOBALS['$L']["New customer"] ?>
      </button>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='LINKTOCREATEANACCOUNT';" class="btn btn-primary"
        style="margin:10px;">
        <?= $GLOBALS['$L']["New artisan"] ?>
      </button>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='LINKTOCREATEANACCOUNT';" class="btn btn-primary"
        style="margin:10px;">
        <?= $GLOBALS['$L']["New designer"] ?>
      </button>
    </div>
    <a href="./createNewAccountAsCustomer.php">Visit AAAAA!</a>
  <?php
  lowerPartOfThePage([]);
  ?>
    <script>
      //form inserted parameters
      const form = document.querySelector('form');
      const insertedEmal = document.getElementById('insertedEmal');
      const insertedPassword = document.getElementById('insertedPassword');

      function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
      }

      //prevent sending form with errors
      form.onsubmit = function(e){
        if(insertedEmal.value === ""){
          e.preventDefault();
          alert("<?= $GLOBALS['$L']["You have missed to insert the email address"] ?>");
        } else if(!isValidEmail(insertedEmal.value)){
          e.preventDefault();
          alert("<?= $GLOBALS['$L']["Email address not valid"] ?>");
        } else if(insertedPassword.value === ""){
          e.preventDefault();
          alert("<?= $GLOBALS['$L']["You have missed to insert the password"] ?>");
        }
      }
    </script>
  <?php
?>
