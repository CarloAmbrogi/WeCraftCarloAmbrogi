<?php

  //This function is to add a p paragraph
  function addParagraph($written){
    ?>
      <div class="row mb-3">
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

?>
