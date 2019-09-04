<?php

$db = new Admin(Connection::make($config['database']));

if($_POST['id']){
    $db->deleteComments($_POST['id']);
}
echo header('Location:/admin');
exit();