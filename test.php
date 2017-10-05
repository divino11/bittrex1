<?php
session_start();
set_time_limit(0);
//error_reporting(0);     //отображение ошибок на странице
require_once 'db/db.php';
mysqli_set_charset($link, 'utf8');
$time = $_POST['period'];
$sql = "SELECT * FROM `bit` $time";
$result = mysqli_query($link, $sql);
$result1 = array();
$result2 = array();
$result3 = array();
$result4 = array();
$time = array();
while ($row = mysqli_fetch_array($result)) {
    $result1[] = $row['result1'];
    $result2[] = $row['result2'];
    $result3[] = $row['result3'];
    $result4[] = $row['result4'];
    $time[] = $row['date'];
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
    <form action="test.php" method="post" class="formOrder">
        <?php
        require 'func/config.php';
        require_once 'func/functions.php';
        $buyOrSell = $_POST['buyOrSell'];
        if ($buyOrSell == "buy") {
            $dataNumber = $_POST['selectColumn'];
            $_SESSION['file'] = $dataNumber;
            $count = $_POST['count'];
            $file = fopen('json/' . $dataNumber . '.json', "r");
            $getJSON = stream_get_contents($file);
            $json = json_decode($getJSON);
            $sql = mysqli_query($link,"TRUNCATE TABLE `bitGraph`");
            foreach ($json as $item) {
                if ($dataNumber == "data4") {
                    $rate4 = lastPrice($item);
                    $_SESSION['i1000'];
                    $counts4 = ($count / $_SESSION['i1000']) * 0.95;
                    $counts = sprintf('%.8f', $counts4);
                    $_SESSION['count'] = $counts;
                    $price4 = $rate4 / 0.95;
                    $price = sprintf('%.8f', $price4);
                    BuyOrSell($buyOrSell, $item, $counts, $price);
                    mysqli_set_charset($link, 'utf8');
                    $sql = mysqli_query($link, "INSERT INTO `bitGraph` (`namePair`, `price`) 
                        VALUES ('$item', '$price')");
                }
            }
        } elseif ($buyOrSell == "sell") {
            $sql = mysqli_query($link,"TRUNCATE TABLE `bitGraph`");
            $dataNumber = $_POST['selectColumn'];
            $count = $_POST['count'];
            $file = fopen('json/' . $dataNumber . '.json', "r");
            $getJSON = stream_get_contents($file);
            $json = json_decode($getJSON);
            foreach ($json as $item) {
                if ($dataNumber == "data4") {
                    $rate4 = lastPrice($item);
                    $_SESSION['i1000'];
                    $counts4 = ($count / $_SESSION['i1000']) * 0.95;
                    $counts = sprintf('%.8f', $counts4);
                    $price4 = $rate4 * 0.95;
                    $price = sprintf('%.8f', $price4);
                    BuyOrSell($buyOrSell, $item, $counts, $price);
                }
            }
        }
        ?>
        <select name="buyOrSell" class="selectpicker">
            <option value="">Покупка или продажа</option>
            <option value="buy">Покупка</option>
            <option value="sell">Продажа</option>
        </select>
        <select name="selectColumn" class="selectpicker">
            <option value="">Выберите колонку</option>
            <option value="data1">0 - 100</option>
            <option value="data2">100 - 200</option>
            <option value="data3">200 - 500</option>
            <option value="data4">>500</option>
        </select>
        <input type="text" name="count" class="input_field" placeholder="Количество в BTC (5)">
        <button type="submit" class="btn btn-info">Выбрать</button>
    </form>
    <form action="index.php" method="post" class="formGraphic">
        <select name="period">
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE)">Последние 30 мин</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)">Последний час</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 HOUR)">Последние 5 часов</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 HOUR)">Последние 10 часов</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)">Последние сутки</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 7 DAY)">Последняя неделя</option>
            <option value=" WHERE date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 DAY)">Последний месяц</option>
        </select>
        <button class="btn btn-info">Выбрать</button>
    </form>
</div>
<div id="container1"></div>
<div id="container2"></div>
<div id="container3"></div>
<div id="container4"></div>
<pre id="data1" hidden>Date,Construction output
    <?php
    $arr = array_combine($time, $result1);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data2" hidden>Date,Construction output
    <?php
    $arr = array_combine($time, $result2);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data3" hidden>Date,Construction output
    <?php
    $arr = array_combine($time, $result3);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<pre id="data4" hidden>Date,Construction output
    <?php
    $arr = array_combine($time, $result4);
    foreach ($arr as $key => $value) {
        echo $key;
        echo ",";
        echo $value . "\n";
    }
    ?>
</pre>
<script>
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
<script>
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
<script>
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
<script>
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
<!--<script>
    Highcharts.chart('container2', {

        title: {
            text: '100 - 200'
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                type: 'flag'
            }
        },

        series: [{
            name: 'value',
            data: [<?php /*echo join($result2, ',') */ ?>]
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
<script>
    Highcharts.chart('container3', {

        title: {
            text: '200 - 500'
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                type: 'flag'
            }
        },

        series: [{
            name: 'value',
            data: [<?php /*echo join($result3, ',') */ ?>]
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
<script>
    Highcharts.chart('container4', {

        title: {
            text: '>500'
        },
        yAxis: {
            title: {
                text: 'Change'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                type: 'flag'
            }
        },

        series: [{
            name: 'value',
            data: [<?php /*echo join($result4, ',') */ ?>]
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>-->
</body>
</html>