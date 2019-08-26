<?php
session_start();
require_once 'db.php';

$sql = "SELECT*FROM `comments` ORDER BY id DESC";
$statement = $pdo->prepare($sql);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                        <div class="card-header"><h3>Админ панель</h3></div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Аватар</th>
                                    <th>Имя</th>
                                    <th>Дата</th>
                                    <th>Комментарий</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php if($comments):?>
                                <?php foreach ($comments as $comment) :?>
                                        <?php print_r($comment);?>
                                <tr>
                                    <td>
                                        <?php if(empty($comment['avatar'])):?>
                                            <img src="img/no-user.jpg" alt="" class="img-fluid" width="64" height="64">
                                        <?php else:?>
                                            <img src="profile/user<?php echo $comment['user_id'];?>/<?php echo $comment['avatar'];?>" alt="" class="img-fluid"  width="64" height="64">
                                        <?php endif;?>
                                    </td>
                                    <td><?php echo $comment['name'];?></td>
                                    <td><?php echo date('d/m/Y', strtotime($comment['date']));?></td>
                                    <td><?php echo $comment['text'];?></td>
                                    <td>
                                        <?php if($comment['published'] == 0):?>
                                            <a href="allow.php?id=<?php echo $comment['id'];?>" class="btn btn-success" >Разрешить</a>
                                        <?php else:?>
                                            <a href="disallow.php?id=<?php echo $comment['id'];?>" class="btn btn-warning">Запретить</a>
                                        <?php endif;?>
                                        <a href="delete.php?id=<?php echo $comment['id'];?>" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
