<?php

$db = new Admin(Connection::make($config['database']));

if($_POST['id']){
    $db->manageComments($_POST['id'], 1);
}
echo header('Location:/admin');
exit();