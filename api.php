<?php
session_start();
require_once 'db/db.php';
require 'func/config.php';
require 'func/functions.php';
$i100 = 0;
$i200 = 0;
$i500 = 0;
$i1000 = 0;
$data1 = "data1";
$data2 = "data2";
$data3 = "data3";
$data4 = "data4";
$this_dir = dirname(__FILE__);
foreach (apiCurl()->result as $value) {
    $pairName = $value->MarketName;
    if (preg_match("/BTC/", $pairName)) {
        $lastPrice = sprintf('%.8f', $value->Last);
        $prevDay = sprintf('%.8f', $value->PrevDay);
        $volume = sprintf('%.8f', $value->BaseVolume);
        $change = (($lastPrice / $prevDay) - 1) * 100;
        if ($prevDay != 0) {
            if ($volume <= 100) {
                echo "0 - 100 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Change: " . $change . " | ";
                writeDataToJson($pairName, $this_dir, $data1);
                $sumPercent100 = $change;
                $i100++;
            } elseif ($volume >= 100 && $volume <= 200) {
                echo "100 - 200 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent200 += $change;
                $i200++;
                writeDataToJson($pairName, $this_dir, $data2);
            } elseif ($volume >= 200 && $volume <= 500) {
                echo "200 - 500 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent500 += $change;
                $i500++;
                writeDataToJson($pairName, $this_dir, $data3);
            } elseif ($volume >= 500 && $volume <= 100000) {
                echo "500 - &#8734 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent1000 += $change;
                $i1000++;
                writeDataToJson($pairName, $this_dir, $data4);
            }
        }
    }
}
$sql = mysqli_query($link, "TRUNCATE TABLE `bitCount`");
$sql = mysqli_query($link, "INSERT INTO `bitCount` (`i100`, `i200`, `i500`, `i1000`) 
                        VALUES ('$i100', '$i200', '$i500', '$i1000')");
echo "i100 = $i100, i200 = $i200, i500 = $i500, i1000 = $i1000<br>";
echo "cумма процентов: " . $sumPercent100 . "<br>";
echo "cумма процентов: " . $sumPercent200 . "<br>";
echo "cумма процентов: " . $sumPercent500 . "<br>";
echo "cумма процентов: " . $sumPercent1000 . "<br>";
echo $result100 = $sumPercent100 / $i100;
echo $result200 = $sumPercent200 / $i200;
echo $result500 = $sumPercent500 / $i500;
echo $result1000 = $sumPercent1000 / $i1000;
mysqli_set_charset($link, 'utf8');
$sql = mysqli_query($link, "INSERT INTO `bit` (`result1`, `result2`, `result3`, `result4`) 
                        VALUES ('$result100', '$result200', '$result500', '$result1000')");
?>