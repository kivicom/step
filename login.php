<?php
session_start();
require_once 'functions.php';
$pdo = include 'database/start.php';


function checkEmail($email)
{
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {

        $code .= $chars[mt_rand(0,$clen)];
    }

    return $code;

}
//Проверяем куки на существование
if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])){
    //Если куки существуют достаем из базы данные пользователя где ID = $_COOKIE['id']
    $user = $pdo->getUserId($_COOKIE['id']);

    //Сравниваем данные из кукисов м данными из БД
    if(($user['user_hash'] === $_COOKIE['hash']) && ($user['id'] === $_COOKIE['id'])){
        //Если данные совпадают, то записываем их в сессию
        foreach ($user as $key => $item) {
            $_SESSION['user'][$key] = $item;
        }
        echo header('Location: /');
        exit();
    }else{
        // Если данные не совпали, удаляем куки
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
    }
}else{
    /*Если куков не существует, то проходим процедуру авторизации как полагается. Проверяем на пустые поля*/
    if( !empty( $_POST['email'] ) && !empty( $_POST['password'] ) ) {
        //Делаем выборку из базы данных пользователя по переданному $_POST['email']

        $user = $pdo->getEmail($_POST['email']);

        //Проверяем на валидность формата
        if(checkEmail($_POST['email'])){

            //Проверяем на сравнение с email из $_POST['email'] и Из БД
            if($_POST['email'] === $user['email']){

                //Проверяем на сравнение с password из $_POST['password'] и Из БД
                if(md5($_POST['password']) === $user['password']){

                    //Если данные совпадают, то записываем их в сессию
                    foreach ($user as $key => $item) {
                        $_SESSION['user'][$key] = $item;
                    }

                    //Если пользователь отметил "Запомнить меня"
                    if($_POST['remember']){
                        //Генерируем хеш
                        $hash = md5(generateCode(10));
                        $pdo->getRememberMe($hash, $user['id']);
                        //Записываем в куки
                        setcookie("id", $user['id'], time()+60*60*24*30);
                        setcookie("hash", $hash, time()+60*60*24*30);
                    }

                    echo header('Location: /');
                    exit();
                }else{
                    $_SESSION['pass_err'] = 'Пароль не верный';
                    echo header('Location: /login.php');
                    exit();
                }
            }else{
                $_SESSION['email_err'] = 'Email не найден';
                echo header('Location: /login.php');
                exit();
            }
        }else{
            $_SESSION['email_err'] = 'Неправильный формат E-mail';
            echo header('Location: /login.php');
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                Project
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <form method="POST" action="">

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control is-invalid " name="email"  autocomplete="email" autofocus >
                                        <?php if(isset($_SESSION['email_err'])):?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $_SESSION['email_err']; unset($_SESSION['email_err']);?>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"  autocomplete="current-password">
                                    </div>
                                    <?php if(isset($_SESSION['pass_err'])):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $_SESSION['pass_err']; unset($_SESSION['pass_err']);?>
                                        </div>
                                    <?php endif;?>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                            <label class="form-check-label" for="remember">
                                                Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
