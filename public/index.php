<?php
session_start();


require_once '../database/start.php';
require_once '../routes.php';

$route = $_SERVER['REQUEST_URI'];
$url = Router::getRoute($route, $routes);
$part = explode('/', $url);
$controller = $part[0];
$method = ucfirst(isset($part[1]) ? $part[1] : 'index');
$obj = new $controller(Connection::make($config['database']));
$obj->$method();

?>
