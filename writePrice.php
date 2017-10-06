<?php
require_once 'db/db.php';
require_once 'func/functions.php';
$data = 0;
$count = 0;
$sql = "SELECT data, count FROM `bitOpt`";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$data = $row['data'];
$count = $row['count'];
$this_dir = dirname(__FILE__);
$file = fopen($this_dir.'/json/' . $data . '.json', "r");
$getJSON = stream_get_contents($file);
$json = json_decode($getJSON);
var_dump($getJSON);
var_dump($json);
foreach ($json as $item) {
    $rate = lastPrice($item);
    $multiplyPrice = $count * $rate;
    $sum1 += $multiplyPrice;
    echo " || " . $rate . " || ";
    echo $count . " || ";
    echo $multiplyPrice . " || ";
    echo $sum1;
    echo "<br>";
}
$sum1 = $sum1 * 100;
echo $sum1 = sprintf('%.9f', $sum1);
$sql1 = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum1')");
?>

