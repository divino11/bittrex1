<?php
session_start();
require_once 'db/db.php';
require 'func/config.php';
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
foreach ($obj->result as $value) {
    $pairName = $value->MarketName;
    $lastPrice = sprintf('%.8f', $value->Last);
    $prevDay = sprintf('%.8f', $value->PrevDay);
    $volume = sprintf('%.8f', $value->BaseVolume);
    $change = (($lastPrice / $prevDay) - 1) * 100;
    if ($prevDay != 0) {
        if ($volume <= 100) {
            $pairName1 = $pairName;
            echo "0 - 100 <br>";
            echo "Name: " . $pairName . " | ";
            echo "Volume: " . $volume . " | ";
            echo "Change: " . $change . " | ";
            $sumPercent100 += $change;
            $i100++;
            $file1 = fopen('json/data1.json', w);
            $arrayApi1[] = $pairName1;
            $res1 = json_encode($arrayApi1);
            file_put_contents("json/data1.json", $res1);
            fclose($file1);
            unset($file1);
            echo "<br>";
        } elseif ($volume >= 100 && $volume <= 200) {
            $pairName2 = $pairName;
            echo "100 - 200 <br>";
            echo "Name: " . $pairName . " | ";
            echo "Volume: " . $volume . " | ";
            echo "Change: " . $change . " | ";
            $sumPercent200 += $change;
            $i200++;
            $file2 = fopen('json/data2.json', w);
            $arrayApi2[] = $pairName2;
            $res2 = json_encode($arrayApi2);
            file_put_contents("json/data2.json", $res2);
            fclose($file2);
            unset($file2);
            echo "<br>";
        } elseif ($volume >= 200 && $volume <= 500) {
            $pairName3 = $pairName;
            echo "200 - 500 <br>";
            echo "Name: " . $pairName . " | ";
            echo "Volume: " . $volume . " | ";
            echo "Change: " . $change . " | ";
            $sumPercent500 += $change;
            $i500++;
            $file3 = fopen('json/data3.json', w);
            $arrayApi3[] = $pairName3;
            $res3 = json_encode($arrayApi3);
            file_put_contents("json/data3.json", $res3);
            fclose($file3);
            unset($file3);
            echo "<br>";
        } elseif ($volume >= 500 && $volume <= 100000) {
            $pairName4 = $pairName;
            echo "500 - &#8734 <br>";
            echo "Name: " . $pairName . " | ";
            echo "Volume: " . $volume . " | ";
            echo "Change: " . $change . " | ";
            $sumPercent1000 += $change;
            $i1000++;
            $file4 = fopen('json/data4.json', w);
            $arrayApi4[] = $pairName4;
            $res4 = json_encode($arrayApi4);
            file_put_contents("json/data4.json", $res4);
            fclose($file4);
            unset($file4);
            echo "<br>";
        }
    }
}

echo "i100 = $i100, i200 = $i200, i500 = $i500, i1000 = $i1000<br>";
echo "cумма процентов: " . $sumPercent100 . "<br>";
echo "cумма процентов: " . $sumPercent200 . "<br>";
echo "cумма процентов: " . $sumPercent500 . "<br>";
echo "cумма процентов: " . $sumPercent1000 . "<br>";
$_SESSION['i100'] = $i100;
$_SESSION['i200'] = $i200;
$_SESSION['i500'] = $i500;
$_SESSION['i1000'] = $i1000;

echo $result100 = $sumPercent100 / $i100;
echo $result200 = $sumPercent200 / $i200;
echo $result500 = $sumPercent500 / $i500;
echo $result1000 = $sumPercent1000 / $i1000;
mysqli_set_charset($link, 'utf8');
$sql = mysqli_query($link, "INSERT INTO `bit` (`result1`, `result2`, `result3`, `result4`) 
                        VALUES ('$result100', '$result200', '$result500', '$result1000')");
?>