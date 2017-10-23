<?php
define("DB_HOST","127.0.0.1");
define("DB_LOGIN","root");
define("DB_PASSWORD","");
define("DB_NAME","bittrex");
define("ORDERS_LOG","orders.log");
$link = mysqli_connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_NAME) or die('ERR');
