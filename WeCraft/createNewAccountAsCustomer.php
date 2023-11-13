<?php
  include "components/bodyOfThePage.php";

  //Create new account as customer page
  doInitialScripts();
  upperPartOfThePage($GLOBALS['$L']["Create account"],"./index.php");
  ?>
    <div class="row mb-3">
      <p><?= $GLOBALS['$L']["Create new account as customer"] ?></p>
    </div>

    <div class="row mb-3">
      <p><?= $GLOBALS['$L']["Insert your data:"] ?></p>
      <form>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label"><?= $GLOBALS['$L']["Email address"] ?></label>
          <input class="form-control" id="insertedEmal" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label"><?= $GLOBALS['$L']["Password"] ?></label>
          <input type="password" class="form-control" id="insertedPassword">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label"><?= $GLOBALS['$L']["Confirm password"] ?></label>
          <input type="password" class="form-control" id="insertedConfirmedPassword">
        </div>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label"><?= $GLOBALS['$L']["Name"] ?></label>
          <input class="form-control" id="insertedName">
        </div>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label"><?= $GLOBALS['$L']["Surname"] ?></label>
          <input class="form-control" id="insertedSurname">
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label"><?= $GLOBALS['$L']["Icon"] ?></label>
          <input class="form-control" type="file" id="formFile">
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= $GLOBALS['$L']["Submit"] ?></button>
      </form>
    </div>
    
  <?php
  lowerPartOfThePage([]);
?>
  <script>
    //form inserted parameters
    const form = document.querySelector('form');
    const insertedEmal = document.getElementById('insertedEmal');
    const insertedPassword = document.getElementById('insertedPassword');
    const insertedConfirmedPassword = document.getElementById('insertedConfirmedPassword');
    const insertedName = document.getElementById('insertedName');
    const insertedSurname = document.getElementById('insertedSurname');

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
      } else if(insertedConfirmedPassword.value === ""){
        e.preventDefault();
        alert("<?= $GLOBALS['$L']["You have missed to insert the confirmed password"] ?>");
      } else if(insertedName.value === ""){
        e.preventDefault();
        alert("<?= $GLOBALS['$L']["You have missed to insert the name"] ?>");
      } else if(insertedSurname.value === ""){
        e.preventDefault();
        alert("<?= $GLOBALS['$L']["You have missed to insert the surname"] ?>");
      } else if(insertedPassword.value !== insertedConfirmedPassword.value){
        e.preventDefault();
        alert("<?= $GLOBALS['$L']["Password and confirmed password doesnt match"] ?>");
      }
    }
  </script>
<?php
?>