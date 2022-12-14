<?php

namespace App\Databases\Generators;

use App\Databases\DbTypes\NoSql;
use App\interfaces\Generator;
use MongoDB\Client;

class Mongo extends NoSql implements Generator
{
    private $connection;
    private $count;
    private $connectionError = "";
    private $collection;

    public function __construct(){}   

    public function connect()
    {
        $client = new Client();
        $this->setConnection($client);
    }

    public function execution($query){}

    public function setCollection($collection)
    {
        $this->collection = $this->getConnection()->selectCollection(DB_NAME, $collection);
        // test is db name;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    private function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function setConnectionError($error)
    {
        $this->connectionError = $error;
    }

    public function getConnectionError()
    {
        return $this->connectionError;
    }

    public function getCount()
    {
        return $this->count;
    }

    private function findCount($filter)
    {
        try {
            $count = $this->getCollection()->count($filter);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->count = $count;           
    }

    public function insert($data)
    {
        try {
            $result = $this->getCollection()->insertOne($data);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function insertMany($data)
    {
        try {
            $result = $this->getCollection()->insertMany($data);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function updateOne($data)
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $update = ['$set' => $data];
            $result = $this->getCollection()->updateOne($filter, $update);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function update($data)
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $update = ['$set' => $data];
            $result = $this->getCollection()->updateMany($filter, $update);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function delete()
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->deleteMany($filter);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function deleteOne()
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->deleteOne($filter);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function distinctVal($field)
    {
        try {
            $result = $this->getCollection()->distinct($field, $this->getWhere());
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    private function manageWhere($whereArray, $operand= "and")
    {
        $this->where = $whereArray;
    }

    public function select()
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $this->findCount($filter);
            $result = $this->getCollection()->find($filter, $this->getRow());
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function replace($data)
    {
        // does not need $set -- like UPDATEONE() appears on one element.
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->replaceOne($filter, $data);
        } catch (\Throwable $th) {
            $this->setConnectionError($th);
        }
        $this->setQueryResult($result);
        return $this;
    }
}

