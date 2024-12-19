<?php

namespace application\DBAdapter\Database;

use PDO;

class Database implements DatabaseInterface
{
    private $conn;

    public function __construct($dbconfig)
    {
        $dsn = "mysql:" . http_build_query($dbconfig, '', ';');
        $this->conn = new PDO($dsn, $dbconfig['user'], $dbconfig['pass'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function getDbConnection(): PDO
    {
        return $this->conn;
    }
}
