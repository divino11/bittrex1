<?php
require_once 'db/db.php';
require_once 'func/functions.php';
$data = 0;
$count = 0;
$sum1 = 0;
$this_dir = dirname(__FILE__);
$file = fopen($this_dir . '/json/dataSelect.json', "r");
$getJSON = stream_get_contents($file);
$json = json_decode($getJSON);
//var_dump($getJSON);
//var_dump($json);
$sql = "SELECT * FROM `bitAmount`";
$result = mysqli_query($link, $sql);
foreach ($json as $item) {
    $rate = lastPrice($item) / 0.95;
    $row = mysqli_fetch_array($result);
    $countFromSQL = $row['amount'];
    $multiplyPrice = $countFromSQL * $rate;
    $sum1 += $multiplyPrice;
    echo "$countFromSQL | ";
    echo "$rate <br>";
}
echo " || " . $rate . " || ";
echo $countFromSQL . " || ";
echo $multiplyPrice . " || ";
echo $sum1;
echo "<br>";
$sum1 = sprintf('%.9f', $sum1);
$sql1 = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum1')");
?>

