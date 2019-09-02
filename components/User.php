<?php

class User
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Register($name, $email,$password){
        $query = "INSERT INTO `users` (`name`, `email`, `password`) VALUE (?, ?, ?)";
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute(array($name, $email, $password));
        return $result;
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

    public function updPassword($id, $password)
    {
        $query = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array(md5($password), $id));
    }
}