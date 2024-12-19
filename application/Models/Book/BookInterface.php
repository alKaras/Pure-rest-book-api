<?php

namespace application\Models\Book;

interface BookInterface
{
    public function getId();

    public function getTitle();

    public function getAuthor();

    public function getPubDate();

    public function getTable();
}