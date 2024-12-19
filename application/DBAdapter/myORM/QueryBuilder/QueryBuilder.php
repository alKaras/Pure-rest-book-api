<?php

namespace application\DBAdapter\myORM\QueryBuilder;

use application\DBAdapter\Database\DatabaseInterface;

class QueryBuilder implements QueryBuilderInterface
{
    private $pdo;

    private $select = '*';
    private $from;
    private $table;
    private $where = [];
    private $bindings = [];

    private $joins = [];
    private $groupBy = [];
    private $orderBy = [];
    private $sets = [];
    private $query;

    private $orderByType = 'asc';

    private $andOrOperator = null;

    /**
     * @var OperatorCheckerInterface $operatorChecker
     */
    private $operatorChecker;

    /**
     * QueryBuilder constructor
     * @param DatabaseInterface $db
     * @param OperatorCheckerInterface $operatorChecker
     */
    public function __construct(DatabaseInterface $db, OperatorCheckerInterface $operatorChecker)
    {
        $this->pdo = $db->getDbConnection();
        $this->operatorChecker = $operatorChecker;
    }

    /**
     * @param $columns
     * @return $this
     */
    public function select($columns = '*'): static
    {
        $this->select = is_array($columns) ? implode(' ,', $columns) : $columns;
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function from($table): static
    {
        $this->from = $table;
        return $this;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilder|bool
     */
    public function where($column, $operator, $value): static|bool
    {
        $this->where[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;

    }

    public function and(): static
    {
        $this->andOrOperator = 'AND';
        return $this;
    }

    public function or(): static
    {
        $this->andOrOperator = 'OR';
        return $this;
    }

    /**
     * @param $table
     * @param $onFirst
     * @param string $operator
     * @param $second
     * @param string $type
     * @param array $condition
     * @return static
     */
    public function join($table, $onFirst, $operator = '=', $second, $type = 'left', $condition = []): static
    {
        $this->joins[] = "$type join $table on $onFirst $operator $second";
        if (!empty($condition)) {
            $this->joins[] = "$type join $table on $onFirst $operator $second and $condition[0] = $condition[1]";
        }
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function groupBy($table): static
    {
        $this->groupBy[] = is_array($table) ? implode(' ,', $table) : $table;
        return $this;
    }

    /**
     * @param $params
     * @param $type
     * @return mixed
     */
    public function orderBy($params, $type = 'asc')
    {
        $this->orderBy[] = is_array($params) ? implode(' ,', $params) : $params;
        $this->orderByType = $type;
        return $this;
    }

    /**
     * @param $table
     * @return mixed
     */
    public function table($table): static
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        $columns = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $this->table ({$columns}) VALUES ({$values})";
        $this->bindings = array_values($data);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * @return $this
     */
    public function update(): static
    {
        $this->query = "UPDATE $this->table";
        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function set($column, $value): static
    {
        $this->sets[] = "$column = ?";
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE " . implode(' ', $this->where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * @return mixed
     */
    public function updateExec()
    {
        $this->query .= " SET " . implode(', ', $this->sets) . " WHERE " . implode(' ', $this->where);
        $stmt = $this->pdo->prepare($this->query);
        $updatedRes = $stmt->execute($this->bindings);
        if ($updatedRes) {
            $this->cleanStatements();
            return $updatedRes;
        }
        return [];
    }

    /**
     * @throws \Exception
     */
    public function findOrFail($type = 'one'): array|false
    {
        $queryRes = match ($type) {
            'all' => $this->findAll(),
            'one' => $this->findOne(),
            default => throw new \Exception("Not valid fetch type", $type),
        };

        if (!$queryRes) {
            return [];
        }

        $this->cleanStatements();
        return $queryRes;
    }

    private function findOne()
    {
        $sql = "SELECT $this->select FROM {$this->from}";

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->where) {
            if (strtoupper($this->andOrOperator) === 'AND') {
                $sql .= ' WHERE ' . implode(' AND ', $this->where);
            } elseif (strtoupper($this->andOrOperator) == 'OR') {
                $sql .= ' WHERE ' . implode(' OR ', $this->where);
            }
            $sql .= ' WHERE ' . implode(' ', $this->where);
        }
        if ($this->groupBy) {
            $sql .= ' GROUP BY' . implode(' ,', $this->groupBy);
        }
        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(' ,', $this->orderBy) . $this->orderByType;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetch();
    }

    private function findAll()
    {
        $sql = "SELECT $this->select FROM {$this->from}";

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->where) {
            $sql .= ' WHERE ' . implode(' ', $this->where);
        }
        if ($this->groupBy) {
            $sql .= ' GROUP BY' . implode(' ,', $this->groupBy);
        }
        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(' ,', $this->orderBy) . $this->orderByType;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    private function addWhereCondition($object, $column, $operator, $value)
    {
        if ($this->operatorChecker->checkOperator($operator)) {
            $stmtOperator = $this->operatorChecker->makeOperator($operator);
            $stmtCondition = "$column $stmtOperator ?";

            if (empty($this->where)) {
                $this->where[] = $stmtCondition;
            } else {
                $this->where[] = "$object $stmtCondition";
            }
            $this->bindings[] = $value;
        }
    }

    private function cleanStatements()
    {
        $this->where = [];
        $this->bindings = [];
        $this->joins = [];
        $this->groupBy = [];
        $this->orderBy = [];
        $this->sets = [];
    }


}