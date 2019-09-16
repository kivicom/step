<?php
namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use DB\Connection;
use PDO;

class Pagination
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {
        $this->pdo = Connection::make();
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getCommentsOnPagination($table, $itemsPerPage, $currentPage)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->setPaging($itemsPerPage)
            ->page($currentPage)
            ->join('LEFT', 'users', "`users`.id = {$table}.`user_id`")
            ->orderBy(["$table.id DESC"]) ;
        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());
        $comments = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $comments;
    }
}