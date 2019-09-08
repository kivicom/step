<?php
/**
 * Created by PhpStorm.
 * User: Corporation
 * Date: 08.09.2019
 * Time: 16:40
 */

class AdminController
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Index()
    {
        $db = new Admin($this->pdo);
        $comments = $db->getAll();
        return include '../App/views/admin.php';
    }

    public function Disallow()
    {
        $db = new Admin($this->pdo);

        if($_POST['id']){
            $db->manageComments($_POST['id'], 0);
        }
        echo header('Location:/admin');
        exit();
    }

    public function Allow()
    {
        $db = new Admin($this->pdo);

        if($_POST['id']){
            $db->manageComments($_POST['id'], 1);
        }
        echo header('Location:/admin');
        exit();
    }
}