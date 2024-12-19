<?php

namespace application\Models\Book;

class Book implements BookInterface
{

    protected $table = 'books';
    private $id = 0;
    private $title;
    private $author;
    private $pubdate;

    public function __construct($title, $author, $pubdate)
    {
        ++$this->id;
        $this->title = $title;
        $this->author = $author;
        $this->pubdate = $pubdate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getPubDate()
    {
        return $this->pubdate;
    }

    public function getTable()
    {
        return $this->table;
    }
}