<?php
session_start();
set_time_limit(0);
error_reporting(0);     //отображение ошибок на странице
require_once 'db/db.php';
mysqli_set_charset($link, 'utf8');
$time = $_POST['period'];
$countData = 0;
if ($_POST['period'] == null) {
    $sql = "SELECT * FROM `bit` WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE)";
} else
$sql = "SELECT * FROM `bit` $time";
$result = mysqli_query($link, $sql);
$result1 = array();
$result2 = array();
$result3 = array();
$result4 = array();
$avgPrice1 = array();
$avgPrice2 = array();
$avgPrice3 = array();
$avgPrice4 = array();
$time = array();
while ($row = mysqli_fetch_array($result)) {
    $result1[] = $row['result1'];
    $result2[] = $row['result2'];
    $result3[] = $row['result3'];
    $result4[] = $row['result4'];
    $avgPrice1[] = $row['price1'];
    $avgPrice2[] = $row['price2'];
    $avgPrice3[] = $row['price3'];
    $avgPrice4[] = $row['price4'];
    $time[] = $row['date'];
}
if (isset($_POST['dataBtn1'])) {
    $sql = mysqli_query($link, "TRUNCATE TABLE `bitCountData`");
    foreach ($_POST['dataPair'] as $itemData) {
        $this_dir = dirname(__FILE__);
        $countData++;
        $file1 = fopen($this_dir . '/json/dataSelect.json', "w");
        $arrayApi1[] = $itemData;
        $res1 = json_encode($arrayApi1);
        file_put_contents($this_dir . "/json/dataSelect.json", $res1);
        fclose($file1);
        unset($file1);
    }
    $sql = mysqli_query($link, "INSERT INTO `bitCountData` (`dataSelect`)
                    VALUES ('$countData')");
}
if (isset($_POST['dataBtn2'])) {
    $sql = mysqli_query($link, "TRUNCATE TABLE `bitCountData`");
    foreach ($_POST['dataPair'] as $itemData) {
        $this_dir = dirname(__FILE__);
        $countData++;
        $file1 = fopen($this_dir . '/json/dataSelect.json', "w");
        $arrayApi1[] = $itemData;
        $res1 = json_encode($arrayApi1);
        file_put_contents($this_dir . "/json/dataSelect.json", $res1);
        fclose($file1);
        unset($file1);
    }
    $sql = mysqli_query($link, "INSERT INTO `bitCountData` (`dataSelect`)
                    VALUES ('$countData')");
}
if (isset($_POST['dataBtn3'])) {
    $sql = mysqli_query($link, "TRUNCATE TABLE `bitCountData`");
    foreach ($_POST['dataPair'] as $itemData) {
        $this_dir = dirname(__FILE__);
        $countData++;
        $file1 = fopen($this_dir . '/json/dataSelect.json', "w");
        $arrayApi1[] = $itemData;
        $res1 = json_encode($arrayApi1);
        file_put_contents($this_dir . "/json/dataSelect.json", $res1);
        fclose($file1);
        unset($file1);
    }
    $sql = mysqli_query($link, "INSERT INTO `bitCountData` (`dataSelect`)
                    VALUES ('$countData')");
}
if (isset($_POST['dataBtn4'])) {
    $sql = mysqli_query($link, "TRUNCATE TABLE `bitCountData`");
    foreach ($_POST['dataPair'] as $itemData) {
        $this_dir = dirname(__FILE__);
        $countData++;
        $file1 = fopen($this_dir . '/json/dataSelect.json', "w");
        $arrayApi1[] = $itemData;
        $res1 = json_encode($arrayApi1);
        file_put_contents($this_dir . "/json/dataSelect.json", $res1);
        fclose($file1);
        unset($file1);
    }
    $sql = mysqli_query($link, "INSERT INTO `bitCountData` (`dataSelect`)
                    VALUES ('$countData')");
}
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
<div class="container">
    <button class="graph_btn btn btn-info"><a class="graph" href="graph.php" target="_blank">График цен</a></button>
    <form action="index.php" method="post" class="formOrder">
        <?php
        require_once 'db/db.php';
        require 'func/config.php';
        require_once 'func/functions.php';
        $buyOrSell = $_POST['buyOrSell'];
        $allPrice = 0;
        $amount = 0;
        $sum1 = 0;
        $sql = "SELECT * FROM `bitCountData`";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $countNamePair = $row['dataSelect'];
        if ($buyOrSell == "buy") {
            $sql = mysqli_query($link, "TRUNCATE TABLE `bitPercent`");
            $sql = mysqli_query($link, "TRUNCATE TABLE `bitGraphLast`");
            $sql = mysqli_query($link, "TRUNCATE TABLE `bitAmount`");
            $dataNumber = $_POST['selectColumn'];
            $count = $_POST['count'];
            $file = fopen('json/dataSelect.json', "r");
            $getJSON = stream_get_contents($file);
            $json = json_decode($getJSON);
            foreach ($json as $item) {
                $rate = lastPrice($item);
                $counts = ($count / $countNamePair);
                $counts = sprintf('%.8f', $counts);
                $price = $rate / 0.95;
                $price = sprintf('%.8f', $price);
                $amount = ($counts / $price);
                $amount = sprintf('%.8f', $amount);
                $error = BuyOrSell($buyOrSell, $item, $amount, $price)->{'message'};
                echo $error;
                if ("DUST_TRADE_DISALLOWED_MIN_VALUE_50K_SAT" != $error || "INSUFFICIENT_FUNDS" != $error || "QUANTITY_NOT_PROVIDED" != $error || "INVALID_MARKET" != $error) {
                    $amountOnePercent = $amount * 0.01;
                    $arr = [$item => $amountOnePercent];
                    $this_dir = dirname(__FILE__);
                    $file1 = fopen($this_dir . '/json/dataPercent.json', "w");
                    $arrayApi1[] = $arr;
                    $res1 = json_encode($arrayApi1);
                    file_put_contents($this_dir . "/json/dataPercent.json", $res1);
                    fclose($file1);
                    unset($file1);
                    $sql = mysqli_query($link, "INSERT INTO `bitAmount` (`amount`, `amountOnePercent`, `allBTC`) 
                        VALUES ('$amount', '$amountOnePercent', '$count')");
                    $multiplyPrice = $amount * $price;
                    $sum1 += $multiplyPrice;
                }
            }
            $sum1 = sprintf('%.9f', $sum1);
            $sql = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum1')");
        }
        ?>
        <select name="buyOrSell" class="selectpicker">
            <option value="buy">Покупка</option>
        </select>
        <input type="text" name="count" class="input_field" placeholder="Количество в BTC (5)">
        <button type="submit" class="btn btn-info" name="mainFormBtn">Купить</button>
    </form>
    <form action="index.php" method="post" class="formGraphic">
        <select name="period">
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE) " selected>Последние 30 мин
            </option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR) ">Последний час</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 HOUR) ">Последние 5 часов</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 HOUR) ">Последние 10 часов</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY) ">Последние сутки</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 7 DAY) ">Последняя неделя</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 DAY) ">Последний месяц</option>
        </select>
        <button class="btn btn-info">Выбрать</button>
    </form>
</div>
    <section class="graph1">
    <div id="container1"></div>
    <div id="container2"></div>
    <div id="container3"></div>
    <div id="container4"></div>
    </section>
    <div id="container5"></div>
    <div id="container6"></div>
    <div id="container7"></div>
    <div id="container8"></div>
<section class="namePair">
    <div class="row">
        <div class="col-md-3">
            <form action="index.php" method="post">
                    <?php
                    require_once 'db/db.php';
                    $sql = "SELECT * FROM bitData";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $data = $row['data1'];
                        $changePercent = $row['changePercent1'];
                        if ($data != null) {
                            echo "<div class='colorPairName'>";
                            echo $data . "<input type='checkbox' name='dataPair[]' value='" . $data . "' checked>" . " " . round($changePercent, 2);
                            echo "<br>";
                            echo "</div>";
                        }
                    }
                    ?>
                <button type="submit" class="btn btn-info" name="dataBtn1">Выбрать</button>
            </form>
        </div>
        <div class="col-md-3">
            <form action="index.php" method="post">
                    <?php
                    require_once 'db/db.php';
                    $sql = "SELECT * FROM bitData";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $data = $row['data2'];
                        $changePercent = $row['changePercent2'];
                        if ($data != null) {
                            echo "<div class='colorPairName'>";
                            echo $data . "<input type='checkbox' name='dataPair[]' value='" . $data . "' checked>" . " " . round($changePercent, 2);
                            echo "<br>";
                            echo "</div>";
                        }
                    }
                    ?>
                <button type="submit" class="btn btn-info" name="dataBtn2">Выбрать</button>
            </form>
        </div>
        <div class="col-md-3">
            <form action="index.php" method="post">
                    <?php
                    require_once 'db/db.php';
                    $sql = "SELECT * FROM bitData";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $data = $row['data3'];
                        $changePercent = $row['changePercent3'];
                        if ($data != null) {
                            echo "<div class='colorPairName'>";
                            echo $data . "<input type='checkbox' name='dataPair[]' value='" . $data . "' checked>" . " " . round($changePercent, 2);
                            echo "<br>";
                            echo "</div>";
                        }
                    }
                    ?>
                <button type="submit" class="btn btn-info" name="dataBtn3">Выбрать</button>
            </form>
        </div>
        <div class="col-md-3">
            <form action="index.php" method="post">
                    <?php
                    require_once 'db/db.php';
                    $sql = "SELECT * FROM bitData";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $data = $row['data4'];
                        $changePercent = $row['changePercent4'];
                        if ($data != null) {
                            echo "<div class='colorPairName'>";
                            echo $data . "<input type='checkbox' name='dataPair[]' value='" . $data . "' checked>" . " " . round($changePercent, 2);
                            echo "<br>";
                            echo "</div>";
                        }
                    }
                    ?>
                <button type="submit" class="btn btn-info" name="dataBtn4">Выбрать</button>
            </form>
        </div>
    </div>
</section>
<pre id="data1" hidden>Date,0 - 100
    <?php
    $arr = array_combine($time, $result1);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data2" hidden>Date,100 - 200
    <?php
    $arr = array_combine($time, $result2);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data3" hidden>Date,200 - 500
    <?php
    $arr = array_combine($time, $result3);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data4" hidden>Date,>500
    <?php
    $arr = array_combine($time, $result4);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data5" hidden>LastPrice,0 - 100
    <?php
    $arr = array_combine($time, $avgPrice1);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data6" hidden>LastPrice,100 - 200
    <?php
    $arr = array_combine($time, $avgPrice2);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data7" hidden>LastPrice,200 - 500
    <?php
    $arr = array_combine($time, $avgPrice3);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data8" hidden>LastPrice,>500
    <?php
    $arr = array_combine($time, $avgPrice4);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<script async>
    Highcharts.chart('container1', {
        data: {
            csv: document.getElementById('data1').innerHTML
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: '0 - 100'
        }
    });
</script>
<script async>
    Highcharts.chart('container2', {
        data: {
            csv: document.getElementById('data2').innerHTML
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: '100 - 200'
        }
    });
</script>
<script async>
    Highcharts.chart('container3', {
        data: {
            csv: document.getElementById('data3').innerHTML
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: '200 - 500'
        }
    });
</script>
<script async>
    Highcharts.chart('container4', {
        data: {
            csv: document.getElementById('data4').innerHTML
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: '>500'
        }
    });
</script>
<script async>
    Highcharts.chart('container5', {
        data: {
            csv: document.getElementById('data5').innerHTML
        },
        yAxis: {
            title: {
                text: 'Price'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: 'LastPrice 0 - 100'
        }
    });
</script>
<script async>
    Highcharts.chart('container6', {
        data: {
            csv: document.getElementById('data6').innerHTML
        },
        yAxis: {
            title: {
                text: 'Price'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: 'LastPrice 100 - 200'
        }
    });
</script>
<script async>
    Highcharts.chart('container7', {
        data: {
            csv: document.getElementById('data7').innerHTML
        },
        yAxis: {
            title: {
                text: 'Price'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: 'LastPrice 200 - 500'
        }
    });
</script>
<script async>
    Highcharts.chart('container8', {
        data: {
            csv: document.getElementById('data8').innerHTML
        },
        yAxis: {
            title: {
                text: 'Price'
            }
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        title: {
            text: 'LastPrice >500'
        }
    });
</script>
</body>
</html>