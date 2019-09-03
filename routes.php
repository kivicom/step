<?php
$reg1='[0-9-]+';
$reg2='[a-z0-9-]+';
$routes = [
    "/" => "../index.view.php",
    "/login" => "../login.php",
    "/logout" => "../logout.php",
    "/register" => "../register.php",
    "/admin" => "../admin.php",
    "/profile" => "../profile.php",
    "cabinet/user{$reg1}/{$reg2}" => "cabinet/user{$reg1}/{$reg2}",
];
