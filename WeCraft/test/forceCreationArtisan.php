<?php
  include dirname(__FILE__)."/../components/bodyOfThePage.php";
  include dirname(__FILE__)."/../components/miniComponents.php";
  include dirname(__FILE__)."/../functions/costants.php";
  include dirname(__FILE__)."/../functions/functions.php";
  include dirname(__FILE__)."/../database/access.php";
  include dirname(__FILE__)."/../database/functions.php";

  //Page to force a creation of an account as artisan
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
    $insertedShopName = $_POST['insertedShopName'];
    $insertedOpeningHours = $_POST['insertedOpeningHours'];
    $insertedDescription = $_POST['insertedDescription'];
    $insertedPhoneNumber = $_POST['insertedPhoneNumber'];
    $insertedLatitude = $_POST['insertedLatitude'];
    $insertedLongitude = $_POST['insertedLongitude'];
    $insertedAddress = $_POST['insertedAddress'];
    $verificationCode = generateAVerificationCode();
    if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
      //You have chosen to send the file icon
      $fileName = $_FILES["insertedIcon"]["name"];
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
      addANewArtisanWithIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$fileExtension,$imgData,$verificationCode,$insertedShopName,$insertedOpeningHours,$insertedDescription,$insertedPhoneNumber,$insertedLatitude,$insertedLongitude,$insertedAddress);
    } else {
      //create account without file icon
      addANewArtisanWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$verificationCode,$insertedShopName,$insertedOpeningHours,$insertedDescription,$insertedPhoneNumber,$insertedLatitude,$insertedLongitude,$insertedAddress);
    }
    $userId = idUserWithThisEmail($insertedEmail);
    registerEmailVerified($userId);
  }
  include dirname(__FILE__)."/../database/closeConnectionDB.php";
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
      <label for="insertedShopName" class="form-label"><?= translate("Shop name") ?></label>
      <input class="form-control" id="insertedShopName" type="text" name="insertedShopName" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="insertedOpeningHours" class="form-label"><?= translate("Opening hours")." ex: %MonF01:0203:04S05:0607:08" ?></label>
      <input class="form-control" id="insertedOpeningHours" type="text" name="insertedOpeningHours" maxlength="182">
    </div>
    <div class="mb-3">
      <label for="insertedDescription" class="form-label"><?= translate("Description") ?></label>
      <input class="form-control" id="insertedDescription" type="text" name="insertedDescription" maxlength="2046">
    </div>
    <div class="mb-3">
      <label for="insertedPhoneNumber" class="form-label"><?= translate("Phone number") ?></label>
      <input class="form-control" id="insertedPhoneNumber" type="text" name="insertedPhoneNumber" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="insertedLatitude" class="form-label"><?= translate("Latitude") ?></label>
      <input class="form-control" id="insertedLatitude" type="text" name="insertedLatitude" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="insertedLongitude" class="form-label"><?= translate("Longitude") ?></label>
      <input class="form-control" id="insertedLongitude" type="text" name="insertedLongitude" maxlength="24">
    </div>
    <div class="mb-3">
      <label for="insertedAddress" class="form-label"><?= translate("Address") ?></label>
      <input class="form-control" id="insertedAddress" type="text" name="insertedAddress" maxlength="49">
    </div>
    <div class="mb-3">
      <label for="formFile" class="form-label"><?= translate("Icon optional") ?></label>
      <input class="form-control" type="file" id="formFile" name="insertedIcon">
    </div>
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
