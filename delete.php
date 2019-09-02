<?php

require_once 'database/start.php';
require_once 'functions.php';
$db = new Admin(Connection::make($config['database']));

if($_GET['id']){
    $db->deleteComments($_GET['id']);
}
echo header('Location:/admin.php');
exit();