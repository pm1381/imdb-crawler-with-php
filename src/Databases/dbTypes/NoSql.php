<?php

namespace App\Databases\DbTypes;

use App\Databases\Db\Db;

class NoSql extends Db
{

    private $queryResult;

    public function __construct(){}

    public function setQueryResult($result)
    {
        $this->queryResult = $result;
    }

    public function getResult()
    {
        return $this->queryResult;
    }
}
