<?php
session_start();
require_once 'db/db.php';
require 'func/config.php';
$sql = mysqli_query($link, "TRUNCATE TABLE `bitData`");
$nonce = time();
$uri = 'https://bittrex.com/api/v1.1/public/getmarketsummaries?apikey=' . $apikey . '&nonce=' . $nonce;
$sign = hash_hmac('sha512', $uri, $apisecret);
$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$execResult = curl_exec($ch);
$obj = json_decode($execResult);
$i100 = 0;
$i200 = 0;
$i500 = 0;
$i1000 = 0;
$price1 = 0;
$price2 = 0;
$price3 = 0;
$price4 = 0;
$this_dir = dirname(__FILE__);
foreach ($obj->result as $value) {
    $pairName = $value->MarketName;
    if (preg_match("/BTC/", $pairName)) {
        $lastPrice = sprintf('%.8f', $value->Last);
        $prevDay = sprintf('%.8f', $value->PrevDay);
        $volume = sprintf('%.8f', $value->BaseVolume);
        $change = (($lastPrice / $prevDay) - 1) * 100;
        if ($prevDay != 0) {
            if ($volume >= 20 && $volume <= 100) {
                $pairName1 = $pairName;
                $volume100 += $volume;
                echo "20 - 100 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Last: " . $lastPrice . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent100 += $change;
                $price1 += $lastPrice;
                $i100++;
                $file1 = fopen($this_dir . '/json/data1.json', w);
                $arrayApi1[] = $pairName1;
                $res1 = json_encode($arrayApi1);
                file_put_contents($this_dir . "/json/data1.json", $res1);
                fclose($file1);
                unset($file1);
                $sql1 = mysqli_query($link, "INSERT INTO `bitData` (`data1`, `changePercent1`, `volume1`) 
                        VALUES ('$pairName1', '$change', '$volume')");
                echo "<br>";
            } elseif ($volume >= 100 && $volume <= 200) {
                $pairName2 = $pairName;
                $volume200 += $volume;
                echo "100 - 200 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Last: " . $lastPrice . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent200 += $change;
                $price2 += $lastPrice;
                $i200++;
                $file2 = fopen($this_dir . '/json/data2.json', w);
                $arrayApi2[] = $pairName2;
                $res2 = json_encode($arrayApi2);
                file_put_contents($this_dir . "/json/data2.json", $res2);
                fclose($file2);
                unset($file2);
                $sql1 = mysqli_query($link, "INSERT INTO `bitData` (`data2`, `changePercent2`, `volume2`) 
                        VALUES ('$pairName2', '$change', '$volume')");
                echo "<br>";
            } elseif ($volume >= 200 && $volume <= 500) {
                $pairName3 = $pairName;
                $volume500 += $volume;
                echo "200 - 500 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Last: " . $lastPrice . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent500 += $change;
                $price3 += $lastPrice;
                $i500++;
                $file3 = fopen($this_dir . '/json/data3.json', w);
                $arrayApi3[] = $pairName3;
                $res3 = json_encode($arrayApi3);
                file_put_contents($this_dir . "/json/data3.json", $res3);
                fclose($file3);
                unset($file3);
                $sql1 = mysqli_query($link, "INSERT INTO `bitData` (`data3`, `changePercent3`, `volume3`) 
                        VALUES ('$pairName3', '$change', '$volume')");
                echo "<br>";
            } elseif ($volume >= 500 && $volume <= 100000) {
                $pairName4 = $pairName;
                $volume1000 += $volume;
                echo "500 - &#8734 <br>";
                echo "Name: " . $pairName . " | ";
                echo "Volume: " . $volume . " | ";
                echo "Last: " . $lastPrice . " | ";
                echo "Change: " . $change . " | ";
                $sumPercent1000 += $change;
                $price4 += $lastPrice;
                $i1000++;
                $file4 = fopen($this_dir . '/json/data4.json', w);
                $arrayApi4[] = $pairName4;
                $res4 = json_encode($arrayApi4);
                file_put_contents($this_dir . "/json/data4.json", $res4);
                fclose($file4);
                unset($file4);
                $sql1 = mysqli_query($link, "INSERT INTO `bitData` (`data4`, `changePercent4`, `volume4`) 
                        VALUES ('$pairName4', '$change', '$volume')");
                echo "<br>";
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
echo "result100: ";
echo $result100 = $sumPercent100 / $i100;
echo "<br>result200: ";
echo $result200 = $sumPercent200 / $i200;
echo "<br>result500: ";
echo $result500 = $sumPercent500 / $i500;
echo "<br>result1000: ";
echo $result1000 = $sumPercent1000 / $i1000;
echo "<br>avgPrice1: ";
$avgPrice1 = $price1 / $i100;
echo $avgPrice1 = sprintf('%.8f', $avgPrice1);
echo "<br>avgPrice2: ";
$avgPrice2 = $price2 / $i200;
echo $avgPrice2 = sprintf('%.8f', $avgPrice2);
echo "<br>avgPrice3: ";
$avgPrice3 = $price3 / $i500;
echo $avgPrice3 = sprintf('%.8f', $avgPrice3);
echo "<br>avgPrice4: ";
$avgPrice4 = $price4 / $i1000;
echo $avgPrice4 = sprintf('%.8f', $avgPrice4);
mysqli_set_charset($link, 'utf8');
$sql = mysqli_query($link, "INSERT INTO `bit` (`result1`, `result2`, `result3`, `result4`, `price1`, `price2`, `price3`, `price4`, `volume1`, `volume2`, `volume3`, `volume4`) 
                        VALUES ('$result100', '$result200', '$result500', '$result1000', '$avgPrice1', '$avgPrice2', '$avgPrice3', '$avgPrice4', '$volume100', '$volume200', '$volume500', '$volume1000')");
?>