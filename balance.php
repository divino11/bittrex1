<?php
require 'func/config.php';
$nonce = time();
$uri = 'https://bittrex.com/api/v1.1/account/getbalances?apikey=' . $apikey . '&nonce=' . $nonce;
$sign = hash_hmac('sha512', $uri, $apisecret);
$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
$execResult = curl_exec($ch);
$obj = json_decode($execResult);
?>