<?php
require_once 'db/db.php';
require_once 'func/functions.php';
$data = 0;
$count = 0;
$sum1 = 0;
$sum2 = 0;
$this_dir = dirname(__FILE__);
$file = fopen($this_dir . '/json/dataPercent.json', "r");
$getJSON = stream_get_contents($file);
$json = json_decode($getJSON);
//var_dump($getJSON);
//var_dump($json);
$sql = "SELECT * FROM `bitAmount`";
$result = mysqli_query($link, $sql);
foreach ($json as $item) {
    foreach ($item as $namePair => $pricePair) {
        $rate = lastPrice($namePair) / 0.98;
        $row = mysqli_fetch_array($result);
        $countFromSQL = $row['amount'];
        $multiplyPrice = $countFromSQL * $rate;
        $sum1 += $multiplyPrice;
        echo "$countFromSQL | ";
        echo "$rate <br>";
    }
}
$sum1 = sprintf('%.8f', $sum1);
echo "SUM1: " . $sum1;
$sql1 = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum1')");
sleep(20);
$sql2 = "SELECT * FROM `bitAmount`";
$result2 = mysqli_query($link, $sql2);
foreach ($json as $item) {
    foreach ($item as $namePair => $pricePair) {
        $rate = lastPrice($namePair) / 0.98;
        $row2 = mysqli_fetch_array($result2);
        $countFromSQL = $row2['amount'];
        $multiplyPrice = $countFromSQL * $rate;
        $sum2 += $multiplyPrice;
        echo "$countFromSQL | ";
        echo "$rate <br>";
    }
}
$sum2 = sprintf('%.8f', $sum2);
echo "SUM2: " . $sum2;
$sql2 = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum2')");
?>

