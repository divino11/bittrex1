<?php
require_once 'db/db.php';
$apikey = 'd28240d1a67542b8a03d3a89abe187ea';
$apisecret = '839c6c189e2e45cba06f312377d43d6b';
$nonce = time();
$uri = 'https://bittrex.com/api/v1.1/public/getmarketsummaries?apikey=' . $apikey . '&nonce=' . $nonce;
$sign = hash_hmac('sha512', $uri, $apisecret);
$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$execResult = curl_exec($ch);
$obj = json_decode($execResult);


$i1000 = 0;
$i2000 = 0;
$i50000 = 0;
foreach ($obj->result as $value) {
    $pairName = $value->MarketName;
    $lastPrice = sprintf('%.8f', $value->Last);
    $prevDay = sprintf('%.8f', $value->PrevDay);
    $volume = sprintf('%.8f', $value->Volume);
    $change = (($lastPrice / $prevDay) -1) * 100;

    if ($volume <= 1000) {
        $pairName1 = $pairName;
        echo "0 - 1000 <br>";
        echo "Name: " . $pairName . " | ";
        echo "Volume: " . $volume . " | ";
        echo "Change: " . $change . " | ";
        $sumPercent1000 += $change;
        $i1000++;
        $file1 = fopen('pairName1.json', "r");
        $encodeJson[] = array($pairName1);
        $jsonEncode = json_encode($encodeJson);
        file_put_contents('pairName1.json', $jsonEncode);
        fclose($file1);
        echo "<br>";
    } elseif ($volume >= 1000 && $volume <= 2000) {
        echo "1000 - 2000 <br>";
        echo "Name: " . $pairName . " | ";
        echo "Volume: " . $volume . " | ";
        echo "Change: " . $change . " | ";
        $sumPercent2000 += $change;
        $i2000++;
        $file2 = fopen('pairName2.json', "r");
        $encodeJson[] = array($pairName);
        $jsonEncode = json_encode($encodeJson);
        file_put_contents('pairName2.json', $jsonEncode);
        fclose($file2);
        echo "<br>";
    } elseif ($volume >= 2000 && $volume <= 50000) {
        echo "2000 - 50000 <br>";
        echo "Name: " . $pairName . " | ";
        echo "Volume: " . $volume . " | ";
        echo "Change: " . $change . " | ";
        $sumPercent50000 += $change;
        $i50000++;
        $file3 = fopen('pairName3.json', "r");
        $encodeJson[] = array($pairName);
        $jsonEncode = json_encode($encodeJson);
        file_put_contents('pairName3.json', $jsonEncode);
        fclose($file3);
        echo "<br>";
    }
}
echo "i1000 = $i1000, i2000 = $i2000, i50000 = $i50000 <br>";
echo "cумма процентов: " . $sumPercent1000 . "<br>";
echo "cумма процентов: " . $sumPercent2000 . "<br>";
echo "cумма процентов: " . $sumPercent50000 . "<br>";

echo $result1000 = $sumPercent1000 / $i1000;
echo $result2000 = $sumPercent2000 / $i2000;
echo $result50000 = $sumPercent50000 / $i50000;
mysqli_set_charset($link, 'utf8');
$sql = mysqli_query($link, "INSERT INTO `bit` (`result1`, `result2`, `result3`) 
                        VALUES ('$result1000', '$result2000', '$result50000')");


?>