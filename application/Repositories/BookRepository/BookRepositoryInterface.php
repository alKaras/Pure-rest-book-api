<?php

namespace application\Repositories\BookRepository;

use application\Models\Book\BookInterface;

interface BookRepositoryInterface
{
    public function save(BookInterface $book);

    public function create(BookInterface $book);

    public function updateSelf(BookInterface $book);

    public function findById($id);

    public function delete(BookInterface $book);

    public function findAll();

}