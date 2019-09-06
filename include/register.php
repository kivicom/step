<?php

$db = new User(Connection::make($config['database']));

if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirmation'])){

    $is_email = $db->getEmail($_POST['email']);

    if(isEmail($_POST['email'], $is_email)){
        $_SESSION['email_err'] = 'Пользователь с таким E-mail уже существует';
        echo header('Location: /register');
        exit();
    }


    //print_r($emails);die();
    if(!checkEmail($_POST['email'])){
        $_SESSION['email_err'] = 'Неправильный формат E-mail';
        echo header('Location: /register');
        exit();
    }



    if(!checkPassword($_POST['password'], $_POST['password_confirmation'])){
        $_SESSION['pass_err'] = 'Пароли не совпадают или пароль содержит менее 6 символов';
        echo header('Location: /register');
        exit();
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $password_confirmation = md5($_POST['password_confirmation']);

    $query = $db->Register($name, $email,$pass);

    if($query){
        $_SESSION['register_success'] = 'Вы успешно зарегистрировались. Теперь можете авторизоваться.';
        echo header('Location: /login');
        exit();
    }else{
        $_SESSION['register_error'] = 'Ошибка решистрации. Пожалуйста, повторите позже.';
        echo header('Location : /register');
        exit();
    }
}