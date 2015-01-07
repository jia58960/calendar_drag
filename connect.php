<?php

$host = "localhost";
$db_user = "root";
$db_pass = "19893260";
$db_name = "calendar";
$timezone = "Asia/Shanghai";

$link = mysqli_connect($host, $db_user, $db_pass, $db_name) or die("Error " . mysqli_error($link)); //数据库连接资源
mysqli_set_charset($link, utf8); //设置编码

header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set($timezone); //北京时间