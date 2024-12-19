<?php

namespace application\routes;

use application\Controllers\BookController;

class Routes
{
    /**
     * @var BookController
     */
    private $bookController;

    public function __construct(BookController $bookController)
    {
        $this->bookController = $bookController;
    }

    public function control($method, ?string $id)
    {
        if ($id){
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest($method, $id){

        if ($method == "GET") {
            $this->bookController->fetchById($id);
        }
    }

    private function processCollectionRequest($method){
        switch ($method){
            case "GET":
                $this->bookController->fetchBooks();
                break;
            case "POST":
                $this->bookController->createBook();
                break;
        }
    }
}