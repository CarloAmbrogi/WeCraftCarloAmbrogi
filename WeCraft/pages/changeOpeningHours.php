<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change opening hours
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  if($kindOfTheAccountInUse != "Artisan"){
    //This page is visible only to artisans
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is visible only to artisans"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change the opening hours
      $insertedOpeningHours = $_POST['insertedOpeningHours'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if(analyzeStringOpeningHours($insertedOpeningHours)["validity"] == false){
        addParagraph(translate("Opening hours are wrong"));
      } else {
        //Update shop name
        updateOpeningHoursOfAnArtisan($_SESSION["userId"],$insertedOpeningHours);
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page change opening hours
      //Title Change opening hours
      addTitle(translate("Change opening hours"));
      //Form to insert data to change opening hours
      startForm1();
      startForm2($_SERVER['PHP_SELF']);
      ?>
        <!-- Insert opening hours -->
        <p><?= translate("Insert the opening hours:") ?></p>
        <?php
          $days = daysInAWeek;
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
      <?php
      ?>
        <input type="hidden" name="insertedOpeningHours" id="insertedOpeningHours" value="error">
      <?php
        endForm(translate("Submit"));
      ?>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
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

          //prevent sending form with errors
          form.onsubmit = function(e){
            prepareStringOpeningHours();
            insertedOpeningHours.value = preparedStringOpeningHours;
            if(preparedStringOpeningHours == "error"){
              e.preventDefault();
              alert("<?= translate("Opening hours are wrong") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
