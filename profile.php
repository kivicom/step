<?php
session_start();

if(!isset($_SESSION['user']['id'])){
    echo header('Location: /login.php');
    exit();
}

print_r($_SESSION);
require_once 'db.php';

//получаем из базы запись пользователя
$sql = "SELECT * FROM `users` WHERE id = ? LIMIT 1";
$statement = $pdo->prepare($sql);
$statement->execute(array($_SESSION['user']['id']));
$user = $statement->fetch(PDO::FETCH_ASSOC);

function checkEmail($email)
{
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function isEmail($email, $is_email){
    if(in_array($email, $is_email)){
        return true;
    }
    return false;
}

//проверяем обязательные поля на пустоту
if(!empty($_POST['name']) && !empty($_POST['email'])){

    //Проверяем, отличается ли новый емейл от того, что хранится в базе
    if($_POST['email'] !== $user['email']){

        //Если был введён новый емейл - проверяем, соответствует ли он определённому формату
        if(!checkEmail($_POST['email'])){
            $_SESSION['email_err'] = 'Неправильный формат E-mail';
            echo header('Location: /profile.php');
            exit();
        }

        $query = "SELECT `email` FROM `users` WHERE `email` = :email";
        $statement = $pdo->prepare($query);
        $statement->execute(array(':email' => $_POST['email']));
        $is_email = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Проверяем так же, занят ли он кем-либо
        if(isEmail($_POST['email'], $is_email)){
            $_SESSION['email_err'] = 'Пользователь с таким E-mail уже существует';
            echo header('Location: /profile.php');
            exit();
        }

        if(!empty($_FILES['image'])){
            $uploaddir = __DIR__ . '/profile/';
            $userDir = 'user'.$_SESSION['user']['id'];
            if(!file_exists($userDir)){
                @mkdir($uploaddir . $userDir . '/');
            }
            $file = explode(".", $_FILES['image']['name']);
            $filename = md5($file[0]) . '.' . $file[1];
            $isFile = array_diff(scandir($uploaddir . $userDir, 1), ['.','..']);

            if(!empty($isFile)){
                if(file_exists($uploaddir . $userDir . '/' . $isFile[0])){
                    unlink($uploaddir . $userDir . '/' . $isFile[0]);
                    unset($_SESSION['user']['avatar']);
                }
            }

            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir . $userDir . '/' . $filename)){
                $_SESSION['user']['avatar'] = $filename;
            }
        }

        $query = "UPDATE `users` SET `name`= :name, `email` = :email, `avatar` = :avatar WHERE id = :id";
        $statement = $pdo->prepare($query);
        $result = $statement->execute(array(':name' => $_POST['name'], ':email' => $_POST['email'], ':avatar' => $filename, ':id' => $_SESSION['user']['id']));

        if($result){
            $_SESSION['success'] = 'Профиль успешно обновлен';
            $_SESSION['user']['name'] = $_POST['name'];
            $_SESSION['user']['email'] = $_POST['email'];
        }
        echo header('Location:/profile.php');
        exit();
    }else{
        $_SESSION['email_err'] = 'Вы ввели прежний E-mail';
        echo header('Location: /profile.php');
        exit();
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
            <a class="navbar-brand" href="index.html">
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
                    <?php if(!empty($_SESSION['user'])): ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Выход</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Профиль</a></li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                            <?php if(isset($_SESSION['success'])):?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $_SESSION['success']; unset($_SESSION['success']);?>
                                </div>
                            <?php endif;?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?php echo $_SESSION['user']['name'];?>">

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $_SESSION['user']['email'];?>">
                                            <?php if(isset($_SESSION['email_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['email_err']; unset($_SESSION['email_err']);?>
                                                </span>
                                            <?php endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if(!empty($_SESSION['user']['avatar'])):?>
                                            <img src="profile/user<?php echo $_SESSION['user']['id'];?>/<?php echo $_SESSION['user']['avatar'];?>" alt="" class="img-fluid">
                                        <?php else:?>
                                            <img src="img/no-user.jpg" alt="" class="img-fluid">
                                        <?php endif;?>
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-warning">Edit profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                            <div class="alert alert-success" role="alert">
                                Пароль успешно обновлен
                            </div>

                            <form action="/profile/password" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <button class="btn btn-success">Submit</button>
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
