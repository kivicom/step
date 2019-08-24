<?php

//Устанавливаем доступы к базе данных:
$driver = 'mysql'; // тип базы данных, с которой мы будем работать
$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
$db_user = 'root'; //имя пользователя, по умолчанию это root
$db_password = ''; //пароль, по умолчанию пустой
$db_name = 'marlinstep'; //имя базы данных
$charset = 'utf8'; // кодировка по умолчанию
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

$dsn = "$driver:host=$host;dbname=$db_name;charset=$charset";
$pdo = new PDO($dsn, $db_user, $db_password, $options);