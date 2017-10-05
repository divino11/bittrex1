<?php
define("DB_HOST","xnode.mysql.ukraine.com.ua");
define("DB_LOGIN","xnode_andr");
define("DB_PASSWORD","2qn3q6zg");
define("DB_NAME","xnode_andr");
define("ORDERS_LOG","orders.log");
$link = mysqli_connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_NAME) or die('ERR');
//https://phpmyadmin.adm.tools/signon.php?user=xnode_andr&password=2qn3q6zg&account=xnode