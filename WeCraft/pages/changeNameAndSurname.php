<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Change name and surname
  doInitialScripts();
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to change name and surname
      $insertedName = $_POST['insertedName'];
      $insertedSurname = $_POST['insertedSurname'];
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else if($insertedName == ""){
        addParagraph(translate("You have missed to insert the name"));
      } else if(strlen($insertedName) > 24){
        addParagraph(translate("The name is too long"));
      } else if($insertedSurname == ""){
        addParagraph(translate("You have missed to insert the surname"));
      } else if(strlen($insertedSurname) > 24){
        addParagraph(translate("The surname is too long"));
      } else {
        //Update name and surname
        updateNameAndSurnameOfAnUser($_SESSION["userId"],$insertedName,$insertedSurname);
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page change name and surname
      $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
      ?>
        <!-- Title Change name and surname -->
        <?php addTitle(translate("Change name and surname")); ?>
        <!-- Form to insert data to change name and surname -->
        <div class="row mb-3">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="insertedName" class="form-label"><?= translate("Name") ?></label>
              <input class="form-control" id="insertedName" type="text" name="insertedName" maxlength="24">
            </div>
            <div class="mb-3">
              <label for="insertedSurname" class="form-label"><?= translate("Surname") ?></label>
              <input class="form-control" id="insertedSurname" type="text" name="insertedSurname" maxlength="24">
            </div>
            <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
            <button id="submit" type="submit" class="btn btn-primary"><?= translate("Submit") ?></button>
          </form>
        </div>
        <script>
          //form inserted parameters
          const form = document.querySelector('form');
          const insertedName = document.getElementById('insertedName');
          const insertedSurname = document.getElementById('insertedSurname');

          //prevent sending form with errors
          form.onsubmit = function(e){
            if(insertedName.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the name") ?>");
            } else if(insertedSurname.value === ""){
              e.preventDefault();
              alert("<?= translate("You have missed to insert the surname") ?>");
            }
          }
        </script>
      <?php
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
