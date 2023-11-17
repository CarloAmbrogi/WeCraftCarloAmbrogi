<?php
  include "components/bodyOfThePage.php";
  include "components/miniComponents.php";
  include "functions/costants.php";
  include "functions/functions.php";
  include "database/access.php";
  include "database/functions.php";
  doInitialScripts();
  upperPartOfThePage(translate("Verify email"),"./index.php");
  if(isset($_GET["userid"]) && isset($_GET["verificationCode"])){
    $userId = $_GET["userid"];
    $verificationCode = $_GET["verificationCode"];
    if(checkVerificationCode($userId,$verificationCode)){
      addParagraph(translate("OOOKKKKKKKKKOKKK"));
    } else {
      addParagraph(translate("Verification code not correct or too time has passed"));
    }
  } else {
    addParagraph(translate("The link is wrong"));
  }
  ?>
    <div class="row">
      <button type="button" onclick="window.location='./index.php';" class="btn btn-primary"
        style="margin:10px;">
        <?= translate("Return to home") ?>
      </button>
    </div>
  <?php
  lowerPartOfThePage([]);
  include "database/closeConnectionDB.php";
  ?>
