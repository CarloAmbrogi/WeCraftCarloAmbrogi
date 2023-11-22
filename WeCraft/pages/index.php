<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Home page of WeCraft
  doInitialScripts();
  upperPartOfThePage(translate("Welcome to WeCraft"),"");
  ?>
    <!-- Title Welcome to WeCraft -->
    <?php addTitle(translate("Welcome to WeCraft")); ?>
    <!-- Log in -->
    <div class="row mb-3">
      <p><?= translate("Log in:") ?></p>
      <form>
        <div class="mb-3">
          <label for="insertedEmail" class="form-label"><?= translate("Email address") ?></label>
          <input class="form-control" id="insertedEmail" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="insertedPassword" class="form-label"><?= translate("Password") ?></label>
          <input type="password" class="form-control" id="insertedPassword">
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
      </form>
    </div>
    <!-- Alternative options to log in -->
    <div class="row">
      <p><?= translate("...or...") ?></p>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='LINKTOCONTINUEASGUEST';" class="btn btn-primary"
        style="margin:10px;">
        <?= translate("Continue as guest") ?>
      </button>
    </div>
    <div class="row">
      <p><?= translate("...or...") ?></p>
    </div>
    <div class="row">
      <p><?= translate("Create an account:") ?></p>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='./createNewAccountAsCustomer.php';" class="btn btn-primary"
        style="margin:10px;">
        <?= translate("New customer") ?>
      </button>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='./createNewAccountAsArtisan.php';" class="btn btn-primary"
        style="margin:10px;">
        <?= translate("New artisan") ?>
      </button>
    </div>
    <div class="row">
      <button type="button" onclick="window.location='./createNewAccountAsDesigner.php';" class="btn btn-primary"
        style="margin:10px;">
        <?= translate("New designer") ?>
      </button>
    </div>
  <?php
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
