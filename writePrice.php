<?php
session_start();
require_once 'db/db.php';
require_once 'func/functions.php';
//$sql = mysqli_query($link,"TRUNCATE TABLE `bitGraphLast`");
$data = $_SESSION['file'];
$file = fopen('json/' . $data . '.json', "r");
$getJSON = stream_get_contents($file);
$json = json_decode($getJSON);
$sum1 = 0;
foreach ($json as $item) {
    $rate = lastPrice($item);
    $multiplyPrice = $_SESSION['count'] * $rate;
    $sum1 += $multiplyPrice;
    echo " || " . $rate . " || ";
    echo "|| " . $_SESSION['count'] . " || ";
    echo " || " . $multiplyPrice . " || ";
    echo $sum1;
    echo "<br>";
}
$sum1 = $sum1 * 100;
echo $sum1 = sprintf('%.9f', $sum1);
$sql = mysqli_query($link, "INSERT INTO `bitGraphLast` (`lastPrice`) 
                        VALUES ('$sum1')");
?>

