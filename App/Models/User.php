<?php
namespace  App\Models;
use Aura\SqlQuery\QueryFactory;
use PDO;

class User
{
    private $pdo;
    private $queryFactory;

    public function __construct(\PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function userUpdate($table, $id, $data, $image = '')
    {
        unset( $data['id']);
        unset( $data['edit_user']);

        $data['image'] = $image;

        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());
        $res = $sth->execute($update->getBindValues());
        return $res;
    }

    public function getUserInfo($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $user = $sth->fetch(PDO::FETCH_ASSOC);

        return $user;
    }
}