<?php
$conn = mysqli_connect("18.219.131.60","michal","open","pogoda");
if ($conn-> connect_error) {
  die($conn-> connect_error);
  printf("Nie polaczono");
}else {
  printf("Polaczono");
}
$sql = "SELECT godzina_pomiaru, temperatura FROM pomiar WHERE data_pomiaru >=  CURDATE() - INTERVAL 1 DAY";
if ($result = mysqli_query($conn,$sql)) {
  printf("Gitowa");
  $data = array();
  $godzina = array();
  $index = 0;
  $numRows = mysqli_num_rows($result);
  for ($i=0; $i < $numRows; $i++) {
    $row = mysqli_fetch_array($result,MYSQLI_NUM);
    $data[$index] = $row;
    $godzina[$index] = $data[$index][0];
    $index++;
  }
}else {
  printf("Nie gitowa");
}
$conn-> close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wykres</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="conn.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data-2012-2022.min.js"></script>
  </head>
  <body>
    <div class="header">
      <nav>
        <ul>
          <li><a href="http://18.219.131.60/website/page/home.html">Home</a></li>
          <li><a href="http://18.219.131.60/website/page/projects.html">Projects</a></li>
        </ul>
      </nav>
    </div>
    <div class="box">
      <script src="charts/js/highcharts.js"></script>
      <script src="charts/js/modules/exporting.js"></script>
      <div class="container">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
              <div class="panel-heading">Temperature sensor</div>
              <div class="panel-body">
                <div id="container"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script type="text/javascript">
      var js = <?php echo json_encode($godzina); ?>;
        $(function(){
          $('#container').highcharts({
            chart: {
              type: 'line'
            },
            time: {
              timezone: 'Europe/Warsaw'
            },
            xAxis: {
              title: {
                text: 'Time'
              },
              categories: js
            },
             title: {
              text: 'Last 24h'
            },
            yAxis: {
              title: {
                text: 'Temperature'
              }
            },
            series: [{
              name: 'Celsius',
              data: <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>
            }]
          })
        })
      </script>
    </div>
  </body>
</html>
