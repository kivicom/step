<?php
session_start();



function checkEmail($email)
{
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function checkPassword($pass, $pass_confirm)
{
    if(($pass === $pass_confirm) && (strlen($pass) >= 6)){
        return true;
    }
    return false;
}



if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirmation'])){
    if(!checkEmail($_POST['email'])){
        $_SESSION['email_err'] = 'Неправильный формат E-mail';
        echo header('Location: http://marlinstep.loc/register.php');
        exit();
    }

    if(!checkPassword($_POST['password'], $_POST['password_confirmation'])){
        $_SESSION['pass_err'] = 'Пароли не совпадают или пароль содержит менее 6 символов';
        echo header('Location: http://marlinstep.loc/register.php');
        exit();
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $password_confirmation = md5($_POST['password_confirmation']);

    require_once 'db.php';

    $query = "INSERT INTO `users` (`name`, `email`, `password`) VALUE ('{$name}', '{$email}', '{$pass}')";
    $query = mysqli_query($link, $query);

    if($query){
        $_SESSION['register_success'] = 'Вы успешно зарегистрировались. Теперь можете авторизоваться.';
        echo header('Location: /login.php');
    }else{
        $_SESSION['register_error'] = 'Ошибка решистрации. Пожалуйста, повторите позже.';
        echo header('Location : /register.php');
    }
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
                        <div class="card-header">Register</div>

                        <div class="card-body">
                            <form method="POST" action="">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" >
                                    </div>
                                    <?php if(isset($_SESSION['email_err'])):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $_SESSION['email_err']; unset($_SESSION['email_err']);?>
                                        </div>
                                    <?php endif;?>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control " name="password"  autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                    </div>
                                    <?php if(isset($_SESSION['pass_err'])):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $_SESSION['pass_err']; unset($_SESSION['pass_err']);?>
                                        </div>
                                    <?php endif;?>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
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
