<?php

namespace application;

use application\DBAdapter\Database\Database;
use application\DBAdapter\myORM\DataMapper\DataMapper;
use application\DBAdapter\myORM\QueryBuilder\OperatorChecker;
use application\DBAdapter\myORM\QueryBuilder\QueryBuilder;
use application\Repositories\BookRepository\BookRepository;
use application\routes\Routes;
use application\Controllers\BookController;
use application\Services\BookService\BookService;

class App
{
    public static function run()
    {
        $config = require __DIR__ . '/../config/db_conn.php';
        $parts = explode('/', $_SERVER['REQUEST_URI']);
        if ($parts[1] != 'books') {
            http_response_code(404);
            exit();
        }
        $id = $parts[2] ?? null;
        $routes = new Routes(
            new BookController(
                new BookService(
                    new BookRepository(
                        new DataMapper(
                            new QueryBuilder(
                                new Database($config), new OperatorChecker()
                            )
                        )
                    )
                )
            )
        );
        $routes->control($_SERVER['REQUEST_METHOD'], $id);
    }
}