<?php
session_start();


require_once '../database/start.php';
require_once '../routes.php';

$route = $_SERVER['REQUEST_URI'];
include Router::getRoute($route, $routes);
?>
