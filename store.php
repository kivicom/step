<?php

if(!empty($_POST['name']) && !empty($_POST['text'])){
    require_once 'db.php';

    $table = 'comments'; //задаем имя таблицы в переменной

    $name = $_POST['name'];
    $text = $_POST['text'];

    $query = "INSERT INTO `{$table}` (`name`, `text`) VALUES ('{$name}', '{$text}')";
    $query = mysqli_query($link, $query);
    if($query){
        echo header('Location: http://marlinstep.loc/');
    }else{
        echo 'Сообщение не отправлено!';
    }
}