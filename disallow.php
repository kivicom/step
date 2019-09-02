<?php

require_once 'database/start.php';
require_once 'functions.php';
$db = new Admin(Connection::make($config['database']));

if($_GET['id']){
    $db->manageComments($_GET['id'], 0);
}
echo header('Location:/admin.php');
exit();