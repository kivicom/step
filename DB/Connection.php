<?php
namespace DB;

use PDO;

class Connection
{
    private $pdo;

    public static function make()
    {
        $pdo = new PDO(
            "mysql:host=127.0.0.1; dbname=marlinstep;charset=utf8",
            "root",
            "");

        //$config = require_once '../config.php';
        /*$pdo = new PDO(
            "{$config['database']['connection']}; dbname={$config['database']['database']};charset={$config['database']['charset']}",
            "{$config['database']['username']}",
            "{$config['database']['password']}");*/

        return $pdo;
    }

}