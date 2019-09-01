<?php

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllComments($table)
    {
        $sql = "SELECT*FROM `{$table}` LEFT JOIN `users` ON  {$table}.user_id = users.id ORDER BY users.id DESC";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function getUserId($id)
    {
        $query = "SELECT * FROM `users` WHERE id = :id LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array(':id' => $id));
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getRememberMe($hash, $id)
    {
        //Добавляем в таблицу данного юзера хеш
        $query = "UPDATE `users` SET `user_hash`= :user_hash WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array(':user_hash' => $hash, ':id' => $id));
    }

    public function getEmail($email)
    {
        $query = "SELECT* FROM `users` WHERE `email` = ? LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($_POST['email']));
        $email = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $email;
    }
}