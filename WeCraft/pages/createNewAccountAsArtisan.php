<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Create new account as artisan page
  doInitialScripts();
  upperPartOfThePage(translate("Create account"),"./index.php");
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for creating a new account as customer
    $insertedEmail = $_POST['insertedEmail'];
    $insertedPassword = $_POST['insertedPassword'];
    $insertedConfirmedPassword = $_POST['insertedConfirmedPassword'];
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
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else if($insertedEmail == ""){
      addParagraph(translate("You have missed to insert the email address"));
    } else if(strlen($insertedEmail) > 49){
      addParagraph(translate("The email address is too long"));
    } else if(!isValidEmail($insertedEmail)){
      addParagraph(translate("Email address not valid"));
    } else if($insertedPassword == ""){
      addParagraph(translate("You have missed to insert the password"));
    } else if($insertedConfirmedPassword == ""){
      addParagraph(translate("You have missed to insert the confirmed password"));
    } else if($insertedName == ""){
      addParagraph(translate("You have missed to insert the name"));
    } else if(strlen($insertedName) > 24){
      addParagraph(translate("The name is too long"));
    } else if($insertedSurname == ""){
      addParagraph(translate("You have missed to insert the surname"));
    } else if(strlen($insertedSurname) > 24){
      addParagraph(translate("The surname is too long"));
    } else if($insertedShopName == ""){
      addParagraph(translate("You have missed to insert the shop name"));
    } else if(strlen($insertedShopName) > 24){
      addParagraph(translate("The shop name is too long"));
    } else if(analyzeStringOpeningHours($insertedOpeningHours)["validity"] == false){
      addParagraph(translate("Opening hours are wrong"));
    } else if($insertedDescription == ""){
      addParagraph(translate("You have missed to insert the description"));
    } else if($insertedPhoneNumber == ""){
      addParagraph(translate("You have missed to insert the phone number"));
    } else if(!isValidPhoneNumber($insertedPhoneNumber)){
      addParagraph(translate("Phone number not valid"));
    } else if($insertedLatitude == ""){
      addParagraph(translate("You have missed to insert the latitude"));
    } else if(!isValidCoordinate($insertedLatitude)){
      addParagraph(translate("Latitude not valid"));
    } else if($insertedLongitude == ""){
      addParagraph(translate("You have missed to insert the longitude"));
    } else if(!isValidCoordinate($insertedLongitude)){
      addParagraph(translate("Longitude not valid"));
    } else if($insertedAddress == ""){
      addParagraph(translate("You have missed to insert the address"));
    } else if(strlen($insertedAddress) > 49){
      addParagraph(translate("The address is too long"));
    } else if(strlen($insertedDescription) > 2046){
      addParagraph(translate("The description is too long"));
    } else if($insertedPassword != $insertedConfirmedPassword){
      addParagraph(translate("Password and confirmed password doesnt match"));
    } else if(hasThisEmailAddressBeenUsed($insertedEmail)){
      addParagraph(translate("This email address has been used"));
    } else {     
      $verificationCode = generateAVerificationCode();
      //Check on the file icon
      if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
        //You have chosen to send the file icon
        $fileName = $_FILES["insertedIcon"]["name"];
        $fileType = $_FILES["insertedIcon"]["type"];
        $fileSize = $_FILES['insertedIcon']['size'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $insertCorrectlyTheIcon = false;
        if($fileSize > maxSizeForAFile){
          addParagraph(translate("The file is too big"));
        } else if(!array_key_exists($fileExtension, permittedExtensions)){
          addParagraph(translate("The extension of the file is not an image"));
        } else if(!in_array($fileType, permittedExtensions)){
          addParagraph(translate("The file is not an image"));
        } else {
          $insertCorrectlyTheIcon = true;
          //add new account with file icon
          $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
          addANewArtisanWithIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$fileExtension,$imgData,$verificationCode,$insertedShopName,$insertedOpeningHours,$insertedDescription,$insertedPhoneNumber,$insertedLatitude,$insertedLongitude,$insertedAddress);
          addParagraph(translate("Your data has been loaded correctly"));
          addParagraph(translate("Now to confirm your account you have to click on the link we have sent you via email"));
        }
        if($insertCorrectlyTheIcon == false){
          //create account without file icon (because of error in the icon)
          addANewArtisanWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$verificationCode,$insertedShopName,$insertedOpeningHours,$insertedDescription,$insertedPhoneNumber,$insertedLatitude,$insertedLongitude,$insertedAddress);
          addParagraph(translate("Your data has been loaded correctly except for the icon but you will be able to change your icon later"));
          addParagraph(translate("Now to confirm your account you have to click on the link we have sent you via email"));
        }
      } else {
        //create account without file icon
        addParagraph(translate("Your data has been loaded correctly"));
        addParagraph(translate("Now to confirm your account you have to click on the link we have sent you via email"));
        addANewArtisanWithoutIcon($insertedEmail,$passwordHash,$insertedName,$insertedSurname,$verificationCode,$insertedShopName,$insertedOpeningHours,$insertedDescription,$insertedPhoneNumber,$insertedLatitude,$insertedLongitude,$insertedAddress);
    }
      //send an email to verify the email address
      $userId = idUserWithThisEmail($insertedEmail);
      $generatedLink = WeCraftBaseUrl."pages/verifyEmail.php?userid=".$userId."&verificationCode=".$verificationCode;
      $msg = translate("Click on this link only if it has been requested by you");
      $msg = $msg.": ".$generatedLink;
      mail($insertedEmail,"WeCraft - ".translate("Verify email"),$msg);
    }
  } else {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
    ?>
      <!-- Title Create new account as artisan -->
      <?php addTitle(translate("Create new account as artisan")); ?>
      <!-- Form to insert data to create a new account as artisan -->
      <div class="row mb-3">
        <p><?= translate("Insert your data:") ?></p>
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
            <label for="insertedConfirmedPassword" class="form-label"><?= translate("Confirm password") ?></label>
            <input type="password" class="form-control" id="insertedConfirmedPassword" type="text" name="insertedConfirmedPassword">
          </div>
          <div class="mb-3">
            <label for="insertedName" class="form-label"><?= translate("Name") ?></label>
            <input class="form-control" id="insertedName" type="text" name="insertedName" maxlength="24">
          </div>
          <div class="mb-3">
            <label for="insertedSurname" class="form-label"><?= translate("Surname") ?></label>
            <input class="form-control" id="insertedSurname" type="text" name="insertedSurname" maxlength="24">
          </div>
          <p><?= translate("Insert your artisan data:") ?></p>
          <div class="mb-3">
            <label for="insertedShopName" class="form-label"><?= translate("Shop name") ?></label>
            <input class="form-control" id="insertedShopName" type="text" name="insertedShopName" maxlength="24">
          </div>
          <!-- Insert opening hours -->
          <p><?= translate("Insert the opening hours:") ?></p>
          <?php
            $days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
            foreach ($days as &$el) {
              ?>
                <ul class="list-group">
                  <li class="list-group-item">
                    <div for="inserted<?= $el ?>" class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="inserted<?= $el ?>" name="inserted<?= $el ?>">
                      <label class="form-check-label" for="inserted<?= $el ?>"><?= translate($el."L") ?></label>
                    </div>
                    <div for="inserted<?= $el ?>FirstSlot" class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="inserted<?= $el ?>FirstSlot" name="inserted<?= $el ?>FirstSlot">
                      <label class="form-check-label" for="inserted<?= $el ?>FirstSlot"><?= translate("First slot") ?></label>
                      <label class="form-check-label" for="inserted<?= $el ?>FirstSlotFrom"><?= translate("from") ?></label>
                      <input type="time" id="inserted<?= $el ?>FirstSlotFrom" name="inserted<?= $el ?>FirstSlotFrom">
                      <label class="form-check-label" for="inserted<?= $el ?>FirstSlotTo"><?= translate("to") ?></label>
                      <input type="time" id="inserted<?= $el ?>FirstSlotTo" name="inserted<?= $el ?>FirstSlotTo">
                    </div>
                    <div for="inserted<?= $el ?>SecondSlot" class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="inserted<?= $el ?>SecondSlot" name="inserted<?= $el ?>SecondSlot">
                      <label class="form-check-label" for="inserted<?= $el ?>SecondSlot"><?= translate("Second slot") ?></label>
                      <label class="form-check-label" for="inserted<?= $el ?>SecondSlotFrom"><?= translate("from") ?></label>
                      <input type="time" id="inserted<?= $el ?>SecondSlotFrom" name="inserted<?= $el ?>SecondSlotFrom">
                      <label class="form-check-label" for="inserted<?= $el ?>SecondSlotTo"><?= translate("to") ?></label>
                      <input type="time" id="inserted<?= $el ?>SecondSlotTo" name="inserted<?= $el ?>SecondSlotTo">
                    </div>
                  </li>
                </ul>
              <?php
            }
          ?>
          <!-- End of insert opening hours -->
          <div class="mb-3">
            <label for="insertedDescription" class="form-label"><?= translate("Description") ?></label>
            <textarea class="form-control" id="insertedDescription" rows="3" name="insertedDescription" maxlength="2046"></textarea>
          </div>
          <div class="mb-3">
            <label for="insertedPhoneNumber" class="form-label"><?= translate("Phone number") ?></label>
            <input class="form-control" id="insertedPhoneNumber" type="tel" name="insertedPhoneNumber" maxlength="24">
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
          <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
          <input type="hidden" name="insertedOpeningHours" id="insertedOpeningHours" value="error">
          <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
        </form>
      </div>
      <script>
        //form inserted parameters
        const form = document.querySelector('form');
        const insertedEmail = document.getElementById('insertedEmail');
        const insertedPassword = document.getElementById('insertedPassword');
        const insertedConfirmedPassword = document.getElementById('insertedConfirmedPassword');
        const insertedName = document.getElementById('insertedName');
        const insertedSurname = document.getElementById('insertedSurname');
        const insertedShopName = document.getElementById('insertedShopName');
        //opening hours
        const insertedOpeningHours = document.getElementById('insertedOpeningHours');
        const days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        let insertedD = [];
        let insertedDFirstSlot = [];
        let insertedDFirstSlotFrom = [];
        let insertedDFirstSlotTo = [];
        let insertedDSecondSlot = [];
        let insertedDSecondSlotFrom = [];
        let insertedDSecondSlotTo = [];
        days.forEach((el) => {
          insertedD.push(document.getElementById('inserted'+el));
          insertedDFirstSlot.push(document.getElementById('inserted'+el+'FirstSlot'));
          insertedDFirstSlotFrom.push(document.getElementById('inserted'+el+'FirstSlotFrom'));
          insertedDFirstSlotTo.push(document.getElementById('inserted'+el+'FirstSlotTo'));
          insertedDSecondSlot.push(document.getElementById('inserted'+el+'SecondSlot'));
          insertedDSecondSlotFrom.push(document.getElementById('inserted'+el+'SecondSlotFrom'));
          insertedDSecondSlotTo.push(document.getElementById('inserted'+el+'SecondSlotTo'));
        });
        //end opening hours
        const insertedDescription = document.getElementById('insertedDescription');
        const insertedPhoneNumber = document.getElementById('insertedPhoneNumber');
        const insertedLatitude = document.getElementById('insertedLatitude');
        const insertedLongitude = document.getElementById('insertedLongitude');
        const insertedAddress = document.getElementById('insertedAddress');
        const inputFile = document.getElementById('formFile');

        let preparedStringOpeningHours = "";//save informations relate to opening hours to send them later

        //Prepare the string for the opening hours
        function prepareStringOpeningHours(){
          let str = "";
          let errorFound = false;
          for(let i = 0; i < days.length; i++){
            if(insertedD[i].checked){
              str+="%";
              str+=days[i];
              if(insertedDFirstSlot[i].checked){
                str+="F";
                str+=insertedDFirstSlotFrom[i].value;
                str+=insertedDFirstSlotTo[i].value;
                if(insertedDFirstSlotFrom[i].value === "" || insertedDFirstSlotTo[i].value === ""){
                  errorFound = true;
                } else if (!correctOrder(insertedDFirstSlotFrom[i].value,insertedDFirstSlotTo[i].value)){
                  errorFound = true;
                }
              }
              if(insertedDSecondSlot[i].checked){
                str+="S";
                str+=insertedDSecondSlotFrom[i].value;
                str+=insertedDSecondSlotTo[i].value;
                if(insertedDSecondSlotFrom[i].value === "" || insertedDSecondSlotTo[i].value === ""){
                  errorFound = true;
                } else if (!correctOrder(insertedDSecondSlotFrom[i].value,insertedDSecondSlotTo[i].value)){
                  errorFound = true;
                }
              }
              if(!insertedDFirstSlot[i].checked && !insertedDSecondSlot[i].checked){
                errorFound = true;
              }
              if(insertedDFirstSlot[i].checked && insertedDSecondSlot[i].checked){
                if(!correctOrder(insertedDFirstSlotTo[i].value,insertedDSecondSlotFrom[i].value)){
                  errorFound = true;
                }
              }
            }
          }
          preparedStringOpeningHours = str;
          if(errorFound){
            preparedStringOpeningHours = "error";
          }
        }

        //return if these two times are in the correct order or not
        function correctOrder(a,b){
          if(a === "" || b === "" || a == "" || b == ""){
            return true;
          }
          if(a == b){
            return false;
          }
          let hAStr = a.substring(0,2);
          let minAStr = a.substring(3,5);
          let hA = Number(hAStr);
          let minA = Number(minAStr);
          let hBStr = b.substring(0,2);
          let minBStr = b.substring(3,5);
          let hB = Number(hBStr);
          let minB = Number(minBStr);
          if(hB > hA){
            return true;
          }
          if(hB < hA){
            return false;
          }
          if(minB > minA){
            return true;
          }
          return false;
        }

        function isValidEmail(email){
          const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          return emailRegex.test(email);
        }

        function isValidPhoneNumber(phoneNumber){
          const phoneNumberRegex = /^(\+?)[0-9\ \( \)]+$/;
          return phoneNumberRegex.test(phoneNumber);
        }

        function isValidCoordinate(coordinate){
          const coordinateRegex = /^[0-9]+\.[0-9]+$/;
          return coordinateRegex.test(coordinate);
        }

        function getFileExtension(filename){
          return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
        }

        let emailAddressBeenUsedCheck = false;

        //prevent sending form with errors
        form.onsubmit = function(e){
          prepareStringOpeningHours();
          insertedOpeningHours.value = preparedStringOpeningHours;
          if(insertedEmail.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the email address") ?>");
          } else if(!isValidEmail(insertedEmail.value)){
            e.preventDefault();
            alert("<?= translate("Email address not valid") ?>");
          } else if(insertedPassword.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the password") ?>");
          } else if(insertedConfirmedPassword.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the confirmed password") ?>");
          } else if(insertedName.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the name") ?>");
          } else if(insertedSurname.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the surname") ?>");
          } else if(insertedShopName.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the shop name") ?>");
          } else if(insertedDescription.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the description") ?>");
          } else if(insertedPhoneNumber.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the phone number") ?>");
          } else if(!isValidPhoneNumber(insertedPhoneNumber.value)){
            e.preventDefault();
            alert("<?= translate("Phone number not valid") ?>");
          } else if(insertedLatitude.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the latitude") ?>");
          } else if(!isValidCoordinate(insertedLatitude.value)){
            e.preventDefault();
            alert("<?= translate("Latitude not valid") ?>");
          } else if(insertedLongitude.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the longitude") ?>");
          } else if(!isValidCoordinate(insertedLongitude.value)){
            e.preventDefault();
            alert("<?= translate("Longitude not valid") ?>");
          } else if(insertedAddress.value === ""){
            e.preventDefault();
            alert("<?= translate("You have missed to insert the address") ?>");
          } else if(preparedStringOpeningHours == "error"){
            e.preventDefault();
            alert("<?= translate("Opening hours are wrong") ?>");
          } else if(insertedPassword.value !== insertedConfirmedPassword.value){
            e.preventDefault();
            alert("<?= translate("Password and confirmed password doesnt match") ?>");
          } else if(!emailAddressBeenUsedCheck) {
            //prevent sending form if the email address has been used
            e.preventDefault();
            let requestUrl = "<?= WeCraftBaseUrl ?>api/hasThisEmailAddressBeenUsed.php?thisEmailAddress=" + insertedEmail.value;
            let request = new XMLHttpRequest();
            request.open("GET", requestUrl);
            request.responseType = "json";
            request.send();
            request.onload = function(){
              const result = request.response;
              if(result[0].hasThisEmailAddressBeenUsed == 0){
                emailAddressBeenUsedCheck = true;
                $("#submit").unbind('click').click();
              } else {
                alert("<?= translate("This email address has been used") ?>");
              }
            }
          } else if(inputFile.files[0]){
            let file = inputFile.files[0];
            let fileSize = file.size;
            let fileName = file.name;
            let fileExtension = getFileExtension(fileName);
            const permittedExtensions = ["jpg","jpeg","gif","png","webp","heic"];
            if(fileSize > <?= maxSizeForAFile ?>){
              e.preventDefault();
              alert("<?= translate("The file is too big") ?>");
            } else if(!permittedExtensions.includes(fileExtension)){
              e.preventDefault();
              alert("<?= translate("The extension of the file is not an image") ?>");
            }
          }
        }
      </script>
    <?php
  }
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
?>
