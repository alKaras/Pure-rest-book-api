<?php
namespace application\DBAdapter\myORM\DataMapper;

interface DataMapperInterface
{
    public function prepareInsert($table, $criteria);

    public function prepareUpdate($table, $updateSets, $column, $operator, $value);

    public function prepareSelect($fromTable, $selectCond = '*'): static;

    public function prepareDelete($table, $column, $operator, $value);

    public function findBy($column, $value): static;

    public function findById($value): static;

    public function findByComplex(array $conditions): static;

    public function getOne();

    public function getAll();




}