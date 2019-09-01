<?php

class QueryBuilder
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
        $query = "SELECT * FROM `users` WHERE `email` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($_POST['email']));
        $email = $statement->fetch(PDO::FETCH_ASSOC);
        return $email;
    }

    public function userUpdate($id, $data, $image = "")
    {
        $keys = array_keys($data);

        $string = '';
        foreach ($keys as $key) {
            $string .= $key . ' = :' . $key . ', ';
        }
        $keys = rtrim($string,', ');
        $keys .= isset($image) ? (', avatar = :avatar') : '';
        $data['avatar'] = $image;
        $data['id'] = $id;

        $query = "UPDATE `users` SET {$keys} WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute($data);
        return $result;
    }

    public function Register($name, $email,$password){
        $query = "INSERT INTO `users` (`name`, `email`, `password`) VALUE (?, ?, ?)";
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute(array($name, $email, $password));
        return $result;
    }

    public function liveComments($user_id, $name, $text)
    {
        $query = "INSERT INTO `comments` (`user_id`, `name`, `text`) VALUES (?, ?, ?)";
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute(array($user_id, $name, $text));
        return $result;
    }

    public function manageComments($id, $published)
    {
        $query = "UPDATE `comments` SET `published`= ? WHERE `id` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$published, $id]);
    }

    public function updPassword($id, $password)
    {
        $query = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array(md5($password), $id));
    }
}