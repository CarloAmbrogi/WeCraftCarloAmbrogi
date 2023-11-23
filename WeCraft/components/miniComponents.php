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

?>
