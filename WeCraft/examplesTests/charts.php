<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Page test
  doInitialScripts();
  if(true){
    upperPartOfThePage(translate("Test"),"");
    //Content of this page

    //general statistics of WeCraft
    ?>
      <canvas id="myChart1" style="max-width: 500px;"></canvas>
      <script>
        let ctx1 = document.getElementById("myChart1").getContext("2d");
        let myChart1 = new Chart(ctx1, {
          type: "line",
          data: {
            labels: [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday",
              "Sunday",
            ],
            datasets: [
              {
                label: "work load",
                data: [2, 9, 3, 17, 6, 3, 7],
                backgroundColor: "rgba(153,205,1,0.6)",
              },
              {
                label: "free hours",
                data: [2, 2, 5, 5, 2, 1, 10],
                backgroundColor: "rgba(155,153,10,0.6)",
              },
            ],
          },
        });
      </script>
    <?php

    ?>
      <canvas id="myChart2" style="max-width: 500px;"></canvas>
      <script>
        var ctx2 = document.getElementById("myChart2").getContext('2d');
        var myChart2 = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
              label: '# of Votes',
              data: [12, 19, 3, 5, 2, 3],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
              ],
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

    addLineChart("myId1",["Monday","Tuesday","Wednesday"],["work load","free hours"],[[2, 9, 3],[2, 2, 5]]);
    addLineChart("myId2",["Monday","Tuesday","Wednesday","pippo"],["work load","free hours"],[[2, 9, 3],[2, 2, 5]]);
    addLineChart("myId3",["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],["work load","free hours"],[[2, 9, 3, 17, 6, 3, 7],[2, 2, 5, 5, 2, 1, 10]]);

    addBarChart("myId4","# of Votes",["Red","Blue","Yellow"],[12,19,3]);
    addBarChart("myId5","# of Votes",["Red","Blue","Yellow","Green","Purple","Orange"],[12,19,3,5,2,3]);

    //End of this page
  }
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
