<?php
namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Admin
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*', 'comments.id as cid', 'users.id as uid'])
            ->from($table)
            ->join('LEFT', 'users', "{$table}.`user_id` = `users`.id")
            ->orderBy(["$table.id DESC"]) ;
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $comments = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function manageComments($table, $id, $data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function deleteComments($table, $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

}