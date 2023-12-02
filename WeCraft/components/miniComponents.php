<?php

  //This function is to add a p paragraph
  function addParagraph($written){
    ?>
      <div class="row mb-3">
        <p><?= htmlentities($written) ?></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph and without htmlentities
  function addParagraphUnsafe($written){
    ?>
      <div class="row mb-3">
        <p><?= $written ?></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph without mb-3
  function addParagraphWithoutMb3($written){
    ?>
      <div class="row">
        <p><?= htmlentities($written) ?></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph without mb-3 and without htmlentities
  function addParagraphWithoutMb3Unsafe($written){
    ?>
      <div class="row">
        <p><?= $written ?></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph clickable to send an email
  function addEmailToLink($email){
    ?>
      <div class="row mb-3">
        <p><a href="mailto:<?= $email ?>"><?= $email ?></a></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph clickable to send an email without mb-3
  function addEmailToLinkWithoutMb3($email){
    ?>
      <div class="row">
        <p><a href="mailto:<?= $email ?>"><?= $email ?></a></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph clickable to start a call
  function addTelLink($email){
    ?>
      <div class="row mb-3">
        <p><a href="tel:<?= $email ?>"><?= $email ?></a></p>
      </div>
    <?php
  }

  //This function is to add a p paragraph clickable to start a call without mb-3
  function addTelLinkWithoutMb3($email){
    ?>
      <div class="row">
        <p><a href="tel:<?= $email ?>"><?= $email ?></a></p>
      </div>
    <?php
  }

  //This function is to add an h1 title
  function addTitle($written){
    ?>
      <div class="row mb-3">
        <h1><?= htmlentities($written) ?></h1>
      </div>
    <?php
  }

  //This function is to add a button link
  function addButtonLink($written,$link){
    ?>
      <div class="row">
        <button type="button" onclick="window.location='<?= $link ?>';" class="btn btn-primary"
          style="margin:10px;">
          <?= htmlentities($written) ?>
        </button>
      </div>
    <?php
  }

  //Add an image centered with dimension according to width
  function addImage($src,$alt){
    ?>
      <img src="<?= $src ?>" alt=<?= htmlentities($alt) ?> class="row mb-3" style="width:20%;max-height:300px;display:block;margin-left:auto;margin-right:auto;">
    <?php
  }

  //Add an image laterally (when you use the bootstrap cols)
  function addLateralImage($src,$alt){
    ?>
      <img src="<?= $src ?>" alt=<?= htmlentities($alt) ?> class="row mb-3" style="width:40%;max-height:500px;min-width:100px;display:block;margin-left:auto;margin-right:auto;">
    <?php
  }

  //Add an image laterally (when you use the bootstrap cols) witch is not so hight
  function addLateralImageLow($src,$alt){
    ?>
      <img src="<?= $src ?>" alt=<?= htmlentities($alt) ?> class="row mb-3" style="width:40%;max-height:300px;min-width:100px;display:block;margin-left:auto;margin-right:auto;">
    <?php
  }

  //Add a carosuel with the imges of this user (if there are no images for this user, the carousel is not shown)
  function addCarouselImagesOfThisUser($userId){
    $numberOfImages = getNumberImagesOfThisUser($userId);
    if($numberOfImages > 0){
      $images = getImagesOfThisUser($userId);
      ?>
        <div id="carouselExampleIndicators" class="carousel slide">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <?php
              for($i=1;$i<$numberOfImages;$i++){
                ?>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i ?>" aria-label="Slide <?= $i+1 ?>"></button>
                <?php
              }
            ?>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="<?= blobToFile($images[0]["imgExtension"],$images[0]['image']) ?>" class="d-block w-100" alt="Slide 1">
            </div>
            <?php
              for($i=1;$i<$numberOfImages;$i++){
                ?>
                  <div class="carousel-item">
                    <img src="<?= blobToFile($images[$i]["imgExtension"],$images[$i]['image']) ?>" class="d-block w-100" alt="Slide <?= $i+1 ?>">
                  </div>
                <?php
              }
            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
        <style>
          .carousel .carousel-inner{
            height: 500px;
            background-color: #FBD603;
          }
          .carousel-inner .carousel-item img{
            min-height:200px;
            object-fit: contain;
            max-height: 500px;
            border: 1px solid red;
          }
          @media(max-width:768px){
            .carousel .carousel-inner{
              height: auto;
            }
          }
        </style>
      <?php
    }
  }

  //Start a bootstrap row
  function startRow(){
    ?>
      <div class="row">
    <?php
  }

  //Start a bootstrap col
  function startCol(){
    ?>
      <div class="col">
    <?php
  }

  //End a bootstrap row
  function endRow(){
    ?>
      </div>
    <?php
  }

  //End a bootstrap col
  function endCol(){
    ?>
      </div>
    <?php
  }

  //Add an iframe google maps specifying the latitude and the longitude
  function addIframeGoogleMap($latitude,$longitude){
    ?>
      <iframe src = "https://maps.google.com/maps?q=<?= $latitude ?>,<?= $longitude ?>&hl=es;z=14&amp;output=embed" style="height:300px;width:70%;min-width:300px;"></iframe>
    <?php
  }

  //Add button show / hide
  function addButtonShowHide($written,$id){
    ?>
      <div class="row">
        <button type="button" onclick="showHide<?= $id ?>();" class="btn btn-primary"
          style="margin:10px;">
          <?= htmlentities($written) ?>
        </button>
      </div>
    <?php
  }

  //Start the div for the show / hide button
  function startDivShowHide($id){
    ?>
      <div id="showHide<?= $id ?>">
    <?php
  }

  //End the div for the show / hide button
  function endDivShowHide($id){
    ?>
      </div>
      <script>
        var showHideElement<?= $id ?> = document.getElementById("showHide<?= $id ?>");
        showHideElement<?= $id ?>.style.display = "none";
        function showHide<?= $id ?>() {
          if (showHideElement<?= $id ?>.style.display === "none") {
            showHideElement<?= $id ?>.style.display = "block";
          } else {
            showHideElement<?= $id ?>.style.display = "none";
          }
        }
      </script>
    <?php
  }

  //Add a card ($fileImageToVisualize and $title mandatory)
  function addACard($link,$fileImageToVisualize,$title,$text1,$text2){
    addACardForTheGrid($link,$fileImageToVisualize,$title,$text1,$text2);
  }

  //Start a card grid
  function startCardGrid(){
    ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
    <?php
  }

  //Add a card for the grid ($fileImageToVisualize and $title mandatory)
  function addACardForTheGrid($link,$fileImageToVisualize,$title,$text1,$text2){
    ?>
      <div class="col">
        <?php
          if($link != ""){
        ?>
          <a href="<?= $link ?>" style="text-decoration:none">
        <?php
          }
        ?>
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
              <div class="col-md-4">
                <img src="<?= $fileImageToVisualize ?>" class="img-fluid rounded-start" alt="<?= $title ?>" style="max-height:165px;">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title"><?= $title ?></h5>
                    <?php
                      if($text1 != ""){
                    ?>
                      <p class="card-text"><?= $text1 ?></p>
                    <?php
                      }
                    ?>
                    <?php
                      if($text2 != ""){
                    ?>
                      <p class="card-text"><small class="text-body-secondary"><?= $text2 ?></small></p>
                    <?php
                      }
                    ?>
                </div>
              </div>
            </div>
          </div>
        <?php
          if($link != ""){
        ?>
        </a>
        <?php
          }
        ?>
      </div>
    <?php
  }

  //End a card grid
  function endCardGrid(){
    ?>
      </div>
    <?php
  }

  //Start a form 1 (set the csrf token and start the div to contain the form)
  function startForm1(){
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
    ?>
      <div class="row mb-3">
    <?php
  }

  //Start a form 2 (start the form, specify the action script php)
  function startForm2($action){
    ?>
      <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
    <?php
  }

  //Add a paragraph inside a form
  function addParagraphInAForm($text){
    ?>
      <p><?= $text ?></p>
    <?php
  }

  //Add an email field in a form
  function addEmailField($text,$id,$maxLenghtInsertedText){
    ?>
      <div class="mb-3">
        <label for="<?= $id ?>" class="form-label"><?= $text ?></label>
        <input class="form-control" id="<?= $id ?>" aria-describedby="emailHelp" type="text" name="<?= $id ?>" maxlength="<?= $maxLenghtInsertedText ?>">
      </div>
    <?php
  }

  //Add a tel field in a form
  function addTelField($text,$id,$maxLenghtInsertedText){
    ?>
      <div class="mb-3">
        <label for="<?= $id ?>" class="form-label"><?= $text ?></label>
        <input class="form-control" id="<?= $id ?>" type="tel" name="<?= $id ?>" maxlength="<?= $maxLenghtInsertedText ?>">
      </div>
    <?php
  }

  //Add a password field in a form
  function addPasswordField($text,$id){
    ?>
      <div class="mb-3">
        <label for="<?= $id ?>" class="form-label"><?= $text ?></label>
        <input type="password" class="form-control" id="<?= $id ?>" type="text" name="<?= $id ?>">
      </div>
    <?php
  }

  //Add a short text field in a form
  function addShortTextField($text,$id,$maxLenghtInsertedText){
    ?>
      <div class="mb-3">
        <label for="<?= $id ?>" class="form-label"><?= $text ?></label>
        <input class="form-control" id="<?= $id ?>" type="text" name="<?= $id ?>" maxlength="<?= $maxLenghtInsertedText ?>">
      </div>
    <?php
  }

  //Add a long text field in a form
  function addLongTextField($text,$id,$maxLenghtInsertedText){
    ?>
      <div class="mb-3">
        <label for="<?= $id ?>" class="form-label"><?= $text ?></label>
        <textarea class="form-control" id="<?= $id ?>" rows="3" name="<?= $id ?>" maxlength="<?= $maxLenghtInsertedText ?>"></textarea>
      </div>
    <?php
  }

  //Add file field in a form (id is always: formFile)
  function addFileField($text,$name){
    ?>
      <div class="mb-3">
        <label for="formFile" class="form-label"><?= $text ?></label>
        <input class="form-control" type="file" id="formFile" name="<?= $name ?>">
      </div>
    <?php
  }

  //End form (add submit button, csrf token hidden imput and close the form and the div with the form)
  function endForm($textSubmitButton){
    ?>
          <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">
          <button id="submit" type="submit" class="btn btn-primary"><?= $textSubmitButton ?></button>
        </form>
      </div>
    <?php
  }

?>
