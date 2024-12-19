<?php

namespace application\DBAdapter\myORM\QueryBuilder;

class OperatorChecker implements OperatorCheckerInterface
{
    private $operator;
    private static $validOperators = ['=', '==', '===', '!=', '!==', '<>', '>', '<'];

    /**
     * @param string $operator
     * @return bool
     * @throws LogicOperatorCheckerException
     */
    public function checkOperator($operator)
    {
        if (!in_array($operator, self::$validOperators)) {
            throw new LogicOperatorCheckerException("Operator is not valid", $operator);
        } else {
            return true;
        }
    }

    /**
     * @param $operator
     * @return string
     */
    public function makeOperator($operator)
    {
        switch ($operator){
            case "==":
            case "=":
            case "===":
                $this->operator = '=';
                break;
            case '!=':
            case "<>":
            case '!==':
                $this->operator = '<>';
                break;
            case '>':
                $this->operator = '>';
                break;
            case '<':
                $this->operator = '<';
                break;
        }
        return $this->operator;
    }


}