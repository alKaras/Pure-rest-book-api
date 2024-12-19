<?php

namespace application\DBAdapter\myORM\DataMapper;

use application\DBAdapter\myORM\QueryBuilder\QueryBuilderInterface;

class DataMapper implements DataMapperInterface
{

    private $queryBuilder;


    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function prepareInsert($table, $criteria)
    {
        return $this->queryBuilder->table($table)->insert($criteria);
    }

    public function prepareUpdate($table, $updateSets, $column, $operator, $value)
    {

        return $this->queryBuilder->table($table)->update()
            ->set(key($updateSets), array_values($updateSets))->where($column, $operator, $value)
            ->updateExec();
    }

    public function prepareSelect($fromTable, $selectCond = '*'): static
    {
        $this->queryBuilder->select($selectCond)->from($fromTable);
        return $this;

    }

    public function prepareDelete($table, $column, $operator, $value)
    {
        return $this->queryBuilder->table($table)->where($column, $operator, $value)->delete();
    }


    public function findBy($column, $value): static
    {
        $this->queryBuilder->where($column, '=', $value);
        return $this;

    }

    public function findById($value): static
    {
        $this->queryBuilder->where('id', '=', $value);
        return $this;
    }

    public function findByComplex(array $conditions): static
    {
        if (! empty($conditions['or'])){
            $this->queryBuilder->where($conditions['where']['field'], $conditions['where']['operator'], $conditions['where']['value'])
                ->or()
                ->where($conditions['or']['field'], $conditions['or']['operator'], $conditions['or']['value']);
        } elseif (! empty($conditions['and'])){
            $this->queryBuilder->where($conditions['where']['field'], $conditions['where']['operator'], $conditions['where']['value'])
                ->or()
                ->where($conditions['and']['field'], $conditions['and']['operator'], $conditions['and']['value']);
        }

         $this->queryBuilder->where($conditions['where']['field'], $conditions['where']['operator'], $conditions['where']['value']);
        return $this;
    }

    public function getOne()
    {
        return $this->queryBuilder->findOrFail('one');
    }

    public function getAll()
    {
        return $this->queryBuilder->findOrFail('all');
    }

}