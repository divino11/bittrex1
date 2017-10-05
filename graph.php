<?php
session_start();
require_once 'db/db.php';
mysqli_set_charset($link, 'utf8');
$time = $_POST['period'];
$multiplyPrice = 0;
$sql = "SELECT * FROM `bitGraphLast`";
$result = mysqli_query($link, $sql);
$lastPrice = array();
$time = array();
while ($row = mysqli_fetch_array($result)) {
    $lastPrice[] = $row['lastPrice'];
    $time[] = $row['time'];
}
$str = "'" . implode("', '", $time) . "'";
?>
<html>
<head>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="container5"></div>
<script>
    Highcharts.chart('container5', {
        xAxis: {
            categories: [<?php echo $str; ?>]
        },
        /*yAxis: {
            plotLines: [{ // mark the weekend
                color: 'red',
                width: 2,
                value: echo $_SESSION['countBTC'];
                dashStyle: 'solid'
            }]
        },*/
        series: [{
            name: 'Последняя цена',
            data: [<?php echo join($lastPrice, ', '); ?>],
            color: 'green'
        }],
        plotOptions: {
            series: {
                marker: {
                    enabled: true
                }
            }
        },
        title: {
            text: 'График цен'
        }
    });
</script>
</body>
</html>
