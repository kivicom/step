<?php

class Comment
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($table)
    {
        $sql = "SELECT*FROM `users` LEFT JOIN `{$table}` ON  users.id = {$table}.user_id ORDER BY {$table}.id DESC";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function liveComments($user_id, $name, $text)
    {
        $query = "INSERT INTO `comments` (`user_id`, `name`, `text`) VALUES (?, ?, ?)";
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute(array($user_id, $name, $text));
        return $result;
    }
}