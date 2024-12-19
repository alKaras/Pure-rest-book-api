<?php

namespace application\DBAdapter\myORM\QueryBuilder;

interface QueryBuilderInterface {
    /**
     * @param $columns
     * @return $this
     */
    public function select($columns = '*'): static;

    /**
     * @param $table
     * @return $this
     */
    public function from($table): static;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($column, $operator, $value);

    public function and(): static;

    public function or(): static;

//    /**
//     * @param $column
//     * @param $operator
//     * @param $value
//     * @return $this
//     */
//    public function andWhere($column, $operator, $value): static;
//
//    /**
//     * @param $column
//     * @param $operator
//     * @param $value
//     * @return $this
//     */
//    public function orWhere($column, $operator, $value): static;
//
//

    /**
     * @param $table
     * @param $onFirst
     * @param $operator
     * @param $second
     * @param $type
     * @return $this
     */
    public function join($table, $onFirst, $operator = '', $second, $type = 'left', $condition = null): static;

    /**
     * @param $table
     * @return $this
     */
    public function groupBy($table): static;

    /**
     * @param $params
     * @param $type
     * @return mixed
     */
    public function orderBy($params, $type = 'asc');

    /**
     * @param $table
     * @return mixed
     */
    public function table($table): static;

    /**
     * @param $data
     * @return mixed
     */
    public function insert($data);

    /**
     * @return $this
     */
    public function update(): static;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function set($column, $value): static;

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return array|false
     */
    public function findOrFail($type): array|false;

}