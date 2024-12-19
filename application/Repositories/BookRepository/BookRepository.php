<?php

namespace application\Repositories\BookRepository;

use application\DBAdapter\myORM\DataMapper\DataMapperInterface;
use application\Models\Book\BookInterface;

class BookRepository implements BookRepositoryInterface
{
    /**
     * @var DataMapperInterface $dataMapper
     */
    private $dataMapper;

    public function __construct(DataMapperInterface $mapper)
    {
        $this->dataMapper = $mapper;
    }

    public function save(BookInterface $book)
    {
        $existedBook = $this->getBookById($book->getTable(), $book->getId());
        if (!$existedBook){
            $this->create($book);
        } else {
            $this->updateSelf($book);
        }
    }

    public function create(BookInterface $book)
    {
        return $this->dataMapper->prepareInsert($book->getTable(), [
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'pubdate' => $book->getPubDate()
        ]);
        /*if ($isCreated){
            return $this->dataMapper->prepareSelect('books')->findById($book->getId())->getOne();
        } else {
            return [];
        }*/
    }

    public function updateSelf(BookInterface $book)
    {
        $this->dataMapper->prepareUpdate($book->getTable(),[
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'pubdate' => $book->getPubDate()
        ], 'id', '=', $book->getId());
    }

    public function findById($id)
    {
        return $this->getBookById('books', $id);
    }

    public function findAll()
    {
        return $this->dataMapper->prepareSelect('books')->getAll();
    }

    public function delete(BookInterface $book)
    {
        $this->dataMapper->prepareDelete($book->getTable(), 'id', '=', $book->getId());
    }

    private function getBookById($table, $id){
        return $this->dataMapper->prepareSelect($table)->findById($id)->getOne();
    }
}