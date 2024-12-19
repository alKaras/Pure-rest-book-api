<?php

namespace application\DBAdapter\Database;

use PDO;

interface DatabaseInterface
{
    public function getDbConnection(): PDO;
}