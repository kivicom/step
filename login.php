<?php
session_start();
require_once 'db.php';

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

if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])){
    $query = mysqli_query($link,"SELECT * FROM `users` WHERE id = '{$_COOKIE['id']}' LIMIT 1");
    $user = mysqli_fetch_assoc($query);

    if(($user['user_hash'] !== $_COOKIE['hash']) || ($user['id'] !== $_COOKIE['id'])){
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");

        if( !empty( $_POST['email'] ) && !empty( $_POST['password'] ) ) {
            $query = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '{$_POST['email']}' LIMIT 1");
            $user = mysqli_fetch_assoc($query);
            if(checkEmail($_POST['email'])){
                if($_POST['email'] === $user['email']){
                    if(md5($_POST['password']) === $user['password']){
                        foreach ($user as $key => $item) {
                            $_SESSION['user'][$key] = $item;
                        }
                        if($_POST['remember']){
                            $hash = md5(generateCode(10));
                            mysqli_query($link,"UPDATE `users` SET `user_hash`='".$hash."'  WHERE id='".$user['id']."'");
                            setcookie("id", $user['id'], time()+60*60*24*30);
                            setcookie("hash", $hash, time()+60*60*24*30);
                        }

                        echo header('Location: http://marlinstep.loc/');
                        exit();
                    }else{
                        $_SESSION['pass_err'] = 'Пароль не верный';
                        echo header('Location: http://marlinstep.loc/login.php');
                        exit();
                    }
                }else{
                    $_SESSION['email_err'] = 'Email не найден';
                    echo header('Location: http://marlinstep.loc/login.php');
                    exit();
                }
            }else{
                $_SESSION['email_err'] = 'Неправильный формат E-mail';
                echo header('Location: http://marlinstep.loc/login.php');
                exit();
            }
        }
    }else{
        foreach ($user as $key => $item) {
            $_SESSION['user'][$key] = $item;
        }
    }

}else{

    print "Включите куки";

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

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
