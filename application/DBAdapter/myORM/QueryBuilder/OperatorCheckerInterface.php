<?php

namespace application\DBAdapter\myORM\QueryBuilder;

interface OperatorCheckerInterface
{
    public function checkOperator($operator);

    public function makeOperator($operator);
}