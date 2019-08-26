<?php
require_once 'db.php';

if($_GET['id']){
    $query = "UPDATE `comments` SET `published`= 1 WHERE `id` = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($_GET['id']));
}
echo header('Location:/admin.php');
exit();