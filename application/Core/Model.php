<?php

namespace application\Core;

use application\DBAdapter\myORM\QueryBuilder\QueryBuilderInterface;

class Model {
    protected $queryBuilder;

    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
}