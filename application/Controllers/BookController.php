<?php

namespace application\Controllers;

use application\Core\Controller;
use application\Services\BookService\BookServiceInterface;

class BookController extends Controller
{
    /**
     * @var BookServiceInterface $bookService
     */
    private $bookService;

    public function __construct(BookServiceInterface $bookService)
    {
        $this->bookService = $bookService;
    }

    public function createBook()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->bookService->createBook($data);

        if ($response){
            $this->jsonResponse(["message" => "Book Created successfully", "data" => $response]);
        } else {
            $this->jsonResponse(["message" => "Unable to create book"], 500);
        }

    }

    public function fetchBooks()
    {
        $response = $this->bookService->fetchBooks();
        if ($response){
            $this->jsonResponse([
                "result" => '0',
                "data" => $response,
            ]);
        } else {
            $this->jsonResponse(["message" => "Unable to fetch books"]);
        }

    }

    public function fetchById($bookId)
    {
//        $bookId = json_decode(file_get_contents("php://input"), true);
        $response = $this->bookService->fetchBook($bookId);

        if (!$response){
            $this->jsonResponse([
                "message" => "Unable to fetch book" . $bookId
            ], 500);
        }
        $this->jsonResponse([
            "result" => 0,
            "data" => $response
        ]);
    }
}