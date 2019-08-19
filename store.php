<?php
session_start();

if(!empty($_POST['name']) && !empty($_POST['text'])){
    require_once 'db.php';

    $table = 'comments'; //задаем имя таблицы в переменной

    $name = $_POST['name'];
    $text = $_POST['text'];

    $query = "INSERT INTO `{$table}` (`name`, `text`) VALUES ('{$name}', '{$text}')";
    $query = mysqli_query($link, $query);

    if($query){
        $_SESSION['com_ok'] = 'Комментарий успешно добавлен';
        echo header('Location: http://marlinstep.loc/');
    }else{
        echo 'Сообщение не отправлено!';
        $_SESSION['com_err'] = 'Ошибка. Комментарий не добавлен';
        echo header('Location: http://marlinstep.loc/');
    }
}else{
    if(empty($_POST['name'])){
        $_SESSION['name_err'] = 'Ошибка. Поле имя не заполненно';
    }
    if(empty($_POST['text'])){
        $_SESSION['text_err'] = 'Ошибка. Поле комментарий не заполненно';
    }
    echo header('Location: http://marlinstep.loc/');
}