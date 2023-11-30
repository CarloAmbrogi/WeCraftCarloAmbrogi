<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Delete icon
  doInitialScripts();
  if(getKindOfTheAccountInUse() == "Guest"){
    //This page is not visible if you are a guest
    upperPartOfThePage(translate("Account"),"");
    addParagraph(translate("This page is not visible without being logged in"));
    addButtonLink(translate("Return to home"),"./index.php");
  } else {
    upperPartOfThePage(translate("Account"),"./myWeCraft.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Receive post request to delete your icon
      $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
      //Check on the input form data
      if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
        addParagraph(translate("Error of the csrf token"));
      } else {
        //Delete your icon
        deleteIconOfAnUser($_SESSION["userId"]);
        addParagraph(translate("Done"));
      }
    } else {
      //Content of the page delete icon
      $userInfos = obtainUserInfos($_SESSION["userId"]);
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
        ?>
          <!-- Title Delete icon -->
          <?php addTitle(translate("Delete icon")); ?>
          <!-- Form to insert data to delete the icon -->
          <div class="row mb-3">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
              <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
              <button id="submit" type="submit" class="btn btn-primary"><?= translate("Delete icon") ?></button>
            </form>
          </div>
        <?php
      } else {
        addParagraph(translate("You have not set the icon"));
      }
    }
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
