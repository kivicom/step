<?php
session_start();

require_once 'db.php';

function checkPassword($pass, $pass_confirm)
{
    if(($pass === $pass_confirm) && (strlen($pass) >= 6)){
        return true;
    }
    return false;
}



if(empty($_POST['current']) || empty($_POST['password']) || empty($_POST['password_confirmation'])){
    $_SESSION['empty_err'] = 'Заполните необходимые поля';
    echo header('Location: /profile.php');
    exit();

}else{
    $current = isset($_POST['current']) ? $_POST['current'] : '';
    $new_password = isset($_POST['password']) ? $_POST['password'] : '';
    $password_confirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : '';

    $query = "SELECT `password` FROM `users` WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($_SESSION['user']['id']));
    $currentDB = $statement->fetch(PDO::FETCH_ASSOC);

    if(md5($current) !== $currentDB['password']){
        $_SESSION['newpass_err'] = 'Текущий пароль не верный';
        echo header('Location: /profile.php');
        exit();
    }

    if(!checkPassword($new_password, $password_confirmation)){
        $_SESSION['pass_err'] = 'Пароли не совпадают или пароль содержит менее 6 символов';
        echo header('Location: /profile.php');
        exit();
    }

    $query = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array(md5($_POST['password']), $_SESSION['user']['id']));

    $_SESSION['pass_success'] = 'Пароль успешно обновлен';
    echo header('Location: /profile.php');
    exit();
}

