<?php

$pdo = include 'database/start.php';

if($_GET['id']){
    $pdo->manageComments($_GET['id'], 0);
}
echo header('Location:/admin.php');
exit();