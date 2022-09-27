<?php

namespace App\Databases\DbTypes;

use App\Databases\Db\Db;

class NoSql extends Db
{

    private $queryResult;
    private $collectionName;

    public function __construct(){}

    public function setTable($collection)
    {
        $this->collectionName = $collection;
    }

    public function setQueryResult($result)
    {
        $this->queryResult = $result;
    }

    public function getQueryResult()
    {
        return $this->queryResult;
    }

    public function getCollectionName()
    {
        return $this->collectionName;
    }
}
