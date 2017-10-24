<?php
session_start();
require_once 'func/config.php';
$a = $pass;
if (isset($_POST['login'])) {
    $password = $_POST['api'];
    if ($password == $a){
        $_SESSION['pass'] = $password;
        header('Location: http://xnode.pro/bittrex/index.php');
    }
}
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<form action="start.php" method="post">
    <div class="aligncenter">
    <input type="text" class="start_input" name="api" width="300px">
        <br>
    <button type="submit" class="btn btn-info" name="login">Войти</button>
    </div>
</form>
</body>
</html>
