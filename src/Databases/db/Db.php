<?php

namespace App\Databases\Db;

class Db
{
    private $queryString;
    private $row   = [];
    private $where = [];
    private $limit = [] ;
    private $order = [];
    private $join  = [];
    private $group = [];

    public function __construct(){}

    public function row($row)
    {
        $this->row = $row;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function join($join)
    {
        $this->join = $join;
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
 
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function getQuery()
    {
        return $this->queryString;
    }

    protected function setQuery($query)
    {
        $this->queryString = $query;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getJoin()
    {
        return $this->join;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getGroup()
    {
        return $this->group;
    }
}
