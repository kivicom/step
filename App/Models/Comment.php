<?php
namespace App\Models;
use Aura\SqlQuery\QueryFactory;
use PDO;

class Comment
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
        $select->cols(['*'])
            ->from($table)
            ->join('LEFT', 'users', "`users`.id = {$table}.`user_id`")
            ->orderBy(["$table.id DESC"]) ;
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $comments = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }
    public function addComment($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }
}