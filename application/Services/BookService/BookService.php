<?php

namespace application\Services\BookService;

use application\Models\Book\Book;
use application\Repositories\BookRepository\BookRepositoryInterface;

class BookService implements BookServiceInterface
{
    /**
     * @var BookRepositoryInterface $bookRepository
     */
    private $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function createBook($data)
    {
        $book = new Book(
            $data['title'],
            $data['author'],
            $data['pubdate']
        );
        return $this->bookRepository->create($book);
    }

    public function updateBook($data)
    {
        $book = new Book($data['title'], $data['author'], $data['pubdate']);
        $this->bookRepository->updateSelf($book);
    }
    public function fetchBooks()
    {
        return $this->bookRepository->findAll();
    }

    public function fetchBook($id)
    {
        return $this->bookRepository->findById($id);
    }
}