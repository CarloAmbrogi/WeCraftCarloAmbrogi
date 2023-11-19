<?php
  include "../components/bodyOfThePage.php";
  include "../components/miniComponents.php";
  include "../functions/costants.php";
  include "../functions/functions.php";
  include "../database/access.php";
  include "../database/functions.php";

  //Page to force a creation of an account as customer
  //if you don't insert the password, it is set automatically to a
  //automatically with email address verified
  doInitialScripts();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $insertedEmail = $_POST['insertedEmail'];
    $insertedPassword = $_POST['insertedPassword'];
    if($insertedPassword == ""){
        $insertedPassword = "a";
    }
    $passwordHash = password_hash($insertedPassword, PASSWORD_DEFAULT);
    $insertedName = $_POST['insertedName'];
    $insertedSurname = $_POST['insertedSurname'];
    $verificationCode = generateAVerificationCode();
    if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
      //You have chosen to send the file icon
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
      addANewCustomerWithIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$fileExtension,$imgData,$verificationCode);
    } else {
      //create account without file icon
      addANewCustomerWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$verificationCode);
    }
    $userId = idUserWithThisEmail($insertedEmail);
    registerEmailVerified($userId);
  }
  include "../database/closeConnectionDB.php";
?>

<div class="row mb-3">
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="insertedEmail" class="form-label"><?= translate("Email address") ?></label>
      <input class="form-control" id="insertedEmail" aria-describedby="emailHelp" type="text" name="insertedEmail" maxlength="49">
    </div>
    <div class="mb-3">
      <label for="insertedPassword" class="form-label"><?= translate("Password") ?></label>
      <input type="password" class="form-control" id="insertedPassword" type="text" name="insertedPassword">
    </div>
    <div class="mb-3">
      <label for="insertedName" class="form-label"><?= translate("Name") ?></label>
      <input class="form-control" id="insertedName" type="text" name="insertedName" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="insertedSurname" class="form-label"><?= translate("Surname") ?></label>
      <input class="form-control" id="insertedSurname" type="text" name="insertedSurname" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="formFile" class="form-label"><?= translate("Icon optional") ?></label>
      <input class="form-control" type="file" id="formFile" name="insertedIcon">
    </div>
    <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
    <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
  </form>
</div>
<script>
  form.onsubmit = function(e){
    if(inputFile.files[0]){
      let file = inputFile.files[0];
      let fileSize = file.size;
      if(fileSize > <?= maxSizeForAFile ?>){
        e.preventDefault();
        alert("<?= translate("The file is too big") ?>");
      }
    }
  }
</script>
