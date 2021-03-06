<?php
require_once 'db/db.php';
require_once 'func/functions.php';
require_once 'func/config.php';
$sql = "SELECT percent1, percent2, percent3, percentSell1, percentSell2, percentSell3 FROM `bitPercent`";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$percent1 = $row['percent1'];
$percent2 = $row['percent2'];
$percent3 = $row['percent3'];
$percentSell1 = $row['percentSell1'];
$percentSell2 = $row['percentSell2'];
$percentSell3 = $row['percentSell3'];
if ($percent1 != null && $percent2 != null && $percent3 != null) {
    $sql = "SELECT * FROM `bitGraphLast` ORDER BY id DESC";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $lastCountBTC = $row['lastPrice'];
    $sql = "SELECT * FROM `bitAmount`";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $sumBTC = $row['allBTC'];
    $minusBTC = ($lastCountBTC - $sumBTC) * 100;
    $sql = "SELECT count1, count2, count3 FROM `bitPercent`";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $count1 = $row['count1'];
    $count2 = $row['count2'];
    $count3 = $row['count3'];
    if ($minusBTC >= $percent1 && $percent1 != 0 && $count1 == 0) {
        $this_dir = dirname(__FILE__);
        $file = fopen($this_dir . '/json/dataPercent.json', "r");
        $getJSON = stream_get_contents($file);
        $json = json_decode($getJSON);
        foreach ($json as $value) {
            foreach ($value as $namePair => $pricePair) {
                $rate = lastPrice($namePair);
                $amountPercentCoin = $pricePair * $percentSell1;
                $volume = sprintf('%.8f', $amountPercentCoin);
                $price = $rate * 0.99;
                sellAll('sell', $namePair, $volume, $price);
                $nextPercent = $pricePair * ($percentSell2 + $percentSell3);
                $sql = mysqli_query($link, "UPDATE `bitAmount` SET `amountOnePercent` = $nextPercent");
            }
        }
        $sql = mysqli_query($link, "UPDATE `bitPercent` SET `count1` = '1'");
    } elseif ($minusBTC >= $percent2 && $percent2 != 0 && $count2 == 0) {
        $this_dir = dirname(__FILE__);
        $file = fopen($this_dir . '/json/dataPercent.json', "r");
        $getJSON = stream_get_contents($file);
        $json = json_decode($getJSON);
        foreach ($json as $value) {
            foreach ($value as $namePair => $pricePair) {
                $rate = lastPrice($namePair);
                $amountPercentCoin = $pricePair * $percentSell2;
                $volume = sprintf('%.8f', $amountPercentCoin);
                $price = $rate * 0.99;
                sellAll('sell', $namePair, $volume, $price);
                $nextPercent = $pricePair * $percentSell3;
                $sql = mysqli_query($link, "UPDATE `bitAmount` SET `amountOnePercent` = $nextPercent");
            }
        }
        $sql = mysqli_query($link, "UPDATE `bitPercent` SET `count2` = '1'");
    } elseif ($minusBTC >= $percent3 && $percent3 != 0 && $count3 == 0) {
        $this_dir = dirname(__FILE__);
        $file = fopen($this_dir . '/json/dataPercent.json', "r");
        $getJSON = stream_get_contents($file);
        $json = json_decode($getJSON);
        foreach ($json as $value) {
            foreach ($value as $namePair => $pricePair) {
                $rate = lastPrice($namePair);
                $amountPercentCoin = $pricePair * $percentSell3;
                $volume = sprintf('%.8f', $amountPercentCoin);
                $price = $rate * 0.99;
                sellAll('sell', $namePair, $volume, $price);
                $nextPercent = $pricePair * 100;
                $sql = mysqli_query($link, "UPDATE `bitAmount` SET `amountOnePercent` = $nextPercent");
            }
        }
        $sql = mysqli_query($link, "UPDATE `bitPercent` SET `count3` = '1'");
    }
}
?>