<?php
require_once 'db.php';

if($_GET['id']){
    $query = "DELETE FROM `comments` WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($_GET['id']));
}
echo header('Location:/admin.php');
exit();