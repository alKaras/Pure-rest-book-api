<?php

namespace application\Services\BookService;

interface BookServiceInterface
{
    public function createBook($data);

    public function updateBook($data);

    public function fetchBooks();

    public function fetchBook($id);
}