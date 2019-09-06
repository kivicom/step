<?php
include 'include/profile.php';
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
    <link href="public/css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
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
                        <li class="nav-item"><a class="nav-link" href="../logout">Выход</a></li>
                        <li class="nav-item"><a class="nav-link" href="../profile">Профиль</a></li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../register">Register</a>
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

                            <?php if(isset($_SESSION['field_err'])):?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['field_err']; unset($_SESSION['field_err']);?>
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
                                        <?php if(!empty($user['avatar'])):?>
                                            <img src="cabinet/user<?php echo $_SESSION['user']['id'];?>/<?php echo $user['avatar'];?>" alt="" class="img-fluid">
                                        <?php else:?>
                                            <img src="public/img/no-user.jpg" alt="" class="img-fluid">
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
                            <?php if(isset($_SESSION['pass_success'])):?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION['pass_success']; unset($_SESSION['pass_success']);?>
                            </div>
                            <?php endif;?>

                            <?php if(isset($_SESSION['empty_err'])):?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['empty_err']; unset($_SESSION['empty_err']);?>
                                </div>
                            <?php endif;?>

                            <form action="upd_password.php" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control" id="exampleFormControlInput1">

                                            <?php if(isset($_SESSION['newpass_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['newpass_err']; unset($_SESSION['newpass_err']);?>
                                                </span>
                                            <?php endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                            <?php if(isset($_SESSION['pass_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['pass_err']; unset($_SESSION['pass_err']);?>
                                                </span>
                                            <?php endif;?>
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
