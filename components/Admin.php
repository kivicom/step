<?php


class Admin
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT comments.id as cid, comments.user_id, comments.text, comments.date, comments.published, users.id as uid, users.name, users.avatar FROM `comments` LEFT JOIN `users` ON comments.user_id = users.id ORDER BY comments.id DESC";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function manageComments($id, $published)
    {
        $query = "UPDATE `comments` SET `published`= ? WHERE `id` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$published, $id]);
    }
    public function deleteComments($id)
    {
        $query = "DELETE FROM `comments` WHERE `id` = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($_GET['id']));
    }

}