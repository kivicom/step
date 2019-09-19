<?php
namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Pagination
{
    private $pdo;
    private $queryFactory;

    public function __construct(\PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getItemsOnPagination($table, $itemsPerPage, $currentPage)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*', 'comments.id as cid', 'users.id as uid'])
            ->from('users')
            ->setPaging($itemsPerPage)
            ->page($currentPage)
            ->join('LEFT', $table, "`users`.id = {$table}.`user_id`")
            ->orderBy(["$table.id DESC"]) ;
        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());
        $comments = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $comments;
    }
}