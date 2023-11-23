<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page to verify your email address (you reach this page by clicking on the link you receive via email)
  doInitialScripts();
  upperPartOfThePage(translate("Verify email"),"./index.php");
  //Verify email by clicking on the link received via email
  if(isset($_GET["userid"]) && isset($_GET["verificationCode"])){
    $userId = $_GET["userid"];
    $verificationCode = $_GET["verificationCode"];
    //Check link
    if(checkVerificationCode($userId,$verificationCode)){
      //Verification ok
      registerEmailVerified($userId);
      $userInfos = obtainUserInfos($userId);
      $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
      //Show image
      if(isset($userInfos['icon']) && ($userInfos['icon'] != null)){
        $fileImageToVisualize = blobToFile($userInfos["iconExtension"],$userInfos['icon']);
        ?>
          <div class="card" style="width: 18rem;">
            <img src="<?= $fileImageToVisualize ?>" class="card-img-top" alt=<?= $nameAndSurname ?>>
            <div class="card-body">
              <h5 class="card-title"><?= $nameAndSurname ?></h5>
            </div>
          </div>
        <?php
      }
      //Welcome
      addParagraph(translate("Welcome")." ".$nameAndSurname);
      addParagraph(translate("Your email address is verified and now you can return to home and do the log in"));
    } else {
      addParagraph(translate("Verification code not correct or too time has passed or verification already done"));
    }
  } else {
    addParagraph(translate("The link is wrong"));
  }
  addButtonLink(translate("Return to home"),"./index.php");
  lowerPartOfThePage([]);
  include "./../database/closeConnectionDB.php";
  ?>
