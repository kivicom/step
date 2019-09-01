<?php
session_start();

$db = include 'database/start.php';

if(!empty($_POST['name']) && !empty($_POST['text'])){

    $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '0';
    $name = $_POST['name'];
    $text = $_POST['text'];

    $query = $db->liveComments($user_id, $name, $text);

    if($query){
        $_SESSION['com_ok'] = 'Комментарий успешно добавлен';
        echo header('Location:/');
    }else{
        echo 'Сообщение не отправлено!';
        $_SESSION['com_err'] = 'Ошибка. Комментарий не добавлен';
        echo header('Location: /');
    }
}else{
    if(empty($_POST['name'])){
        $_SESSION['name_err'] = 'Ошибка. Поле имя не заполненно';
    }
    if(empty($_POST['text'])){
        $_SESSION['text_err'] = 'Ошибка. Поле комментарий не заполненно';
    }
    echo header('Location: /');
}