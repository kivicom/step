<?php

class MainController
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Index()
    {
        $db = new Comment($this->pdo);
        $comments = $db->getAll('comments');
        return include '../App/views/index.view.php';
    }
}