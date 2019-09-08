<?php

$routes = [
    "/" => "MainController",
    "/login" => "UserController",
    "/upd" => "UserController/UpdPassword",
    "/logout" => "UserController/logout",
    "/register" => "UserController/register",
    "/admin" => "AdminController",
    "/profile" => "UserController/profile",
    "/allow" => "AdminController/allow",
    "/disallow" => "AdminController/disallow",
    "/delete" => "AdminController/delete",
    "/store" => "CommentController/add",
];
