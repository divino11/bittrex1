<?php
set_time_limit(0);
function BuyOrSell($buyOrSell, $namePair, $counts, $price)
{
    global $apikey;
    global $apisecret;
    $nonce = time();
    $uri = 'https://bittrex.com/api/v1.1/market/' . $buyOrSell . 'limit?apikey=' . $apikey . '&nonce=' . $nonce . '&market=' . $namePair . '&quantity=' . $counts . '&rate=' . $price;
    //echo "buySell = $buyOrSell, NAMEPAIR = $namePair, COUNT = $counts, PRICE = $price";
    $sign = hash_hmac('sha512', $uri, $apisecret);
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $execResult = curl_exec($ch);
    $obj = json_decode($execResult);
    return $obj;
}

function sellAll($buyOrSell, $namePair, $counts, $price)
{
    global $apikey;
    global $apisecret;
    $nonce = time();
    $uri = 'https://bittrex.com/api/v1.1/market/' . $buyOrSell . 'limit?apikey=' . $apikey . '&nonce=' . $nonce . '&market=' . $namePair . '&quantity=' . $counts . '&rate=' . $price;
    //echo "NAMEPAIR = $namePair, COUNT = $counts, PRICE = $price";
    $sign = hash_hmac('sha512', $uri, $apisecret);
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $execResult = curl_exec($ch);
    $obj = json_decode($execResult);
    return $obj;
}

function lastPrice($item)
{
    global $apikey;
    global $apisecret;
    $nonce = time();
    $uri = 'https://bittrex.com/api/v1.1/public/getticker?market=' . $item;
    $sign = hash_hmac('sha512', $uri, $apisecret);
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $execResult = curl_exec($ch);
    $obj1 = json_decode($execResult);
    foreach ($obj1 as $value) {
        $lastPrice = sprintf('%.8f', $value->Last);
    }
    return $lastPrice;
}

?>