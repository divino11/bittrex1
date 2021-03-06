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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<div class="colorPairName">
    <?php
    $file = fopen('json/dataPercent.json', "r");
    $getJSON = stream_get_contents($file);
    $json = json_decode($getJSON);
    foreach ($json as $item) {
        foreach ($item as $namePair => $pricePair) {
            echo $namePair . " ";
        }
    }
    ?>
</div>
<div id="container55"></div>
<form action="graph.php" method="post" class="formSellAll">
    <button type="submit" name="sellAll" class="btn btn-info" id="btn_sell">Продать все</button>
</form>
<div class="container">
    <div class="aligncenter">
        <form action="graph.php" method="post">
            <?php
            $sql = "SELECT * FROM `bitPercent`";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            $percentF = $row['percent1'];
            $percentS = $row['percent2'];
            $percentT = $row['percent3'];
            $percentSellF = $row['percentSell1'];
            $percentSellS = $row['percentSell2'];
            $percentSellT = $row['percentSell3'];
            ?>
            <input type="text" name="percent1" class="input_field" value="<?php echo $percentF; ?>" placeholder="Первый ордер (5)">
            <input type="text" name="percent2" class="input_field" value="<?php echo $percentS; ?>" placeholder="Второй ордер (10)">
            <input type="text" name="percent3" class="input_field" value="<?php echo $percentT; ?>" placeholder="Третий ордер (15)">
            <br><br>
            <input type="text" name="percentSell1" class="input_field" value="<?php echo $percentSellF; ?>" placeholder="20">
            <input type="text" name="percentSell2" class="input_field" value="<?php echo $percentSellS; ?>" placeholder="30">
            <input type="text" name="percentSell3" class="input_field" value="<?php echo $percentSellT; ?>" placeholder="50">
            <br><br>
                <button type="submit" name="cancel" class="btn btn-danger">Отменить</button>
                <button type="submit" name="mainFormBtn" class="btn btn-info">Применить</button>
        </form>
    </div>
</div>
<?php
require_once 'db/db.php';
$sql1 = "SELECT * FROM `bitPercent`";
$result1 = mysqli_query($link, $sql1);
$row1 = mysqli_fetch_array($result1);
$count1 = $row1['count1'];
$count2 = $row1['count2'];
$count3 = $row1['count3'];
$percentSellF = $row1['percentSell1'];
$percentSellS = $row1['percentSell2'];
$percentSellT = $row1['percentSell3'];
if ($count1 == 1) {
    echo "<p class='colorOrder'>Из первого ордера было продано: " . $percentSellF . "%</p>";
    if ($count2 == 1) {
        echo "<p class='colorOrder'>Из второго ордера было продано: " . $percentSellS . "%</p>";
        if ($count3 == 1) {
            echo "<p class='colorOrder'>Из третьего ордера было продано: " . $percentSellT . "%</p>";
        }
    }
}
if (isset($_POST['cancel'])) {
    require_once 'db/db.php';
    $result = mysqli_query($link, "TRUNCATE TABLE `bitPercent`");
}
if (isset($_POST['sellAll'])) {
    require_once 'db/db.php';
    require_once 'func/functions.php';
    require_once 'func/config.php';
    $uri = 'http://xnode.pro/bittrex/balance.php';
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $execResult = curl_exec($ch);
    $obj = json_decode($execResult);
    foreach ($obj as $item) {
        foreach ($item as $value) {
            if ($value->Currency != "BTC" && $value->Currency != "QTUM" && $value->Currency != "USDT") {
                /*echo $value->Currency;
                echo " | ";
                echo $value->Balance;
                echo "<br>";*/
                $pair = "BTC-" . $value->Currency;
                $rate = lastPrice('BTC-' . $value->Currency);
                $price = $rate * 0.98;
                $price = sprintf('%.8f', $price);
                $amount = $value->Available;
                $amount = sprintf('%.8f', $amount);
                sellAll('sell', $pair, $amount, $price);
            }
        }
    }
}
if (isset($_POST['mainFormBtn'])) {
    $percent1 = $_POST['percent1'];
    $percent2 = $_POST['percent2'];
    $percent3 = $_POST['percent3'];
    $percentSell1 = $_POST['percentSell1'];
    $percentSell2 = $_POST['percentSell2'];
    $percentSell3 = $_POST['percentSell3'];
    $sql = mysqli_query($link, "INSERT INTO `bitPercent` (`percent1`, `percent2`, `percent3`, `percentSell1`, `percentSell2`, `percentSell3`, `count1`, `count2`, `count3`) 
                        VALUES ('$percent1', '$percent2', '$percent3', '$percentSell1', '$percentSell2', '$percentSell3', '0', '0', '0')");
}
?>
<script>
    Highcharts.chart('container55', {
        xAxis: {
            categories: [<?php echo $str; ?>]
        },
        series: [{
            name: 'Последняя цена',
            data: [<?php echo join($lastPrice, ', '); ?>],
            color: 'green'
        }],
        plotOptions: {
            series: {
                marker: {
                    enabled: false
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
