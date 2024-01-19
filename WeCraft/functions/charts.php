<?php

  //Useful functions for charts

  //example: addLineChart("myId1",["Monday","Tuesday","Wednesday"],["work load","free hours"],[[2, 9, 3],[2, 2, 5]]);
  //other example: addLineChart("myId2",["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],["work load","free hours"],[[2, 9, 3, 17, 6, 3, 7],[2, 2, 5, 5, 2, 1, 10]]);
  function addLineChart($id,$labels,$labelsDataset,$data){
    ?>
      <canvas id="myChart<?= $id ?>" style="max-width: 600px;"></canvas>
      <script>
        let ctx<?= $id ?> = document.getElementById("myChart<?= $id ?>").getContext("2d");
        let myChart<?= $id ?> = new Chart(ctx<?= $id ?>, {
          type: "line",
          data: {
            labels: [
              <?php
                foreach($labels as &$label){
                  echo '"'.$label.'",';
                }
              ?>
            ],
            datasets: [
              <?php
                $navigation = 0;
                foreach($labelsDataset as &$labelDataset){
                  echo '{';
                  echo 'label: "'.$labelDataset.'",';
                  $dataToInsert = "";
                  $first = true;
                  foreach($data[$navigation] as &$singleNumber){
                    if($first == false){
                      $dataToInsert.=", ";
                    }
                    $dataToInsert.=$singleNumber;
                    $first = false;
                  }
                  echo 'data: ['.$dataToInsert.'],';
                  $n1 = rand(1, 254);
                  $n2 = rand(1, 254);
                  $n3 = rand(1, 254);
                  echo 'backgroundColor: "rgba('.$n1.','.$n2.','.$n3.',0.6)",';
                  echo '},';
                  $navigation++;
                }
              ?>
            ],
          },
        });
      </script>
    <?php
  }

  //example: addBarChart("myId3","# of Votes",["Red","Blue","Yellow"],[12,19,3]);
  //other example: addBarChart("myId4","# of Votes",["Red","Blue","Yellow","Green","Purple","Orange"],[12,19,3,5,2,3]);
  function addBarChart($id,$title,$labels,$data){
    ?>
      <canvas id="myChart<?= $id ?>" style="max-width: 600px;"></canvas>
      <script>
        var ctx<?= $id ?> = document.getElementById("myChart<?= $id ?>").getContext('2d');
        var myChart<?= $id ?> = new Chart(ctx<?= $id ?>, {
          type: 'bar',
          data: {
            <?php
              echo 'labels: [';
              $first = true;
              foreach($labels as &$label){
                if($first == false){
                  echo ', ';
                }
                echo '"'.$label.'"';
                $first = false;
              }
              echo '],';
            ?>
            datasets: [{
              label: '<?= $title ?>',
              <?php
                echo 'data: [';
                $first = true;
                foreach($data as &$singleData){
                  if($first == false){
                    echo ', ';
                  }
                  echo $singleData;
                  $first = false;
                }
                echo '],';
              ?>
              <?php
                $colors = [];
                foreach($labels as &$label){
                  $n1 = rand(1, 254);
                  $n2 = rand(1, 254);
                  $n3 = rand(1, 254);
                  $additiveColor = [$n1,$n2,$n3];
                  array_push($colors,$additiveColor);
                }
                echo 'backgroundColor: [';
                $first = true;
                foreach($colors as &$singleColor){
                  if($first == false){
                    echo ',';
                  }
                  echo "'rgba(".$singleColor[0].", ".$singleColor[1].", ".$singleColor[2].", 0.2)'";
                  $first = false;
                }
                echo '],';
                echo 'borderColor: [';
                $first = true;
                foreach($colors as &$singleColor){
                  if($first == false){
                    echo ',';
                  }
                  echo "'rgba(".$singleColor[0].", ".$singleColor[1].", ".$singleColor[2].", 1)'";
                  $first = false;
                }
                echo '],';
              ?>
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      </script>
    <?php
  }

?>