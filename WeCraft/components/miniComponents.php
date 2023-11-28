<?php

  //This function is to add a p paragraph
  function addParagraph($written){
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
        <p><?= $written ?></p>
      </div>
    <?php
  }

  //This function is to add an h1 title
  function addTitle($written){
    ?>
      <div class="row mb-3">
        <h1><?= $written ?></h1>
      </div>
    <?php
  }

  //This function is to add a button link
  function addButtonLink($written,$link){
    ?>
      <div class="row">
        <button type="button" onclick="window.location='<?= $link ?>';" class="btn btn-primary"
          style="margin:10px;">
          <?= $written ?>
        </button>
    </div>
    <?php
  }

  //Add an image centered with dimension according to width
  function addImage($src,$alt){
    ?>
      <img src="<?= $src ?>" alt=<?= $alt ?> class="row mb-3" style="width:20%;max-height:300px;display:block;margin-left:auto;margin-right:auto;">
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

?>
