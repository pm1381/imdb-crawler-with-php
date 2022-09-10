<?php

namespace App\Databases\Generators;

use App\Databases\DbTypes\Sql;
use App\interfaces\Generator;


class Pdo extends Sql implements Generator
{
    private $connection;
    private $connectionError = ""; 

    public function __construct(){}

    public function connect()
    {
        $dsn = "mysql:host=" . 'localhost' . ";dbname=". 'test' . ";charset=UTF8";
        try {
            $this->setConnection(new \PDO($dsn, 'root', ''));
        } catch (\PDOException $e) {
            $this->setConnectionError($e);
        }
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnectionError()
    {
        return $this->connectionError;
    }

    private function setConnectionError($error)
    {
        $this->connectionError = $error;
    }

    public function execution($query)
    {
        try {
            $query->execute();
        } catch (\PDOException $e) {
            http_response_code(400);
            $this->setConnectionError($e);
        }
    }

    public function insert($data)
    {
        $cols = $this->generateInsertRows($data);
        $bind = $this->generateBinds($data);
        $statement = "INSERT INTO " . '`' . $this->getTable() . '`' . $cols ." VALUES " . $bind;
        $query = $this->getConnection()->prepare($statement);
        $this->setQuery($statement);
        $this->insertBindParams($query, $data);
        $this->execution($query);
        return $this;
    }

    private function insertBindParams($query, $data)
    {
        foreach ($data as $col => $value){
            $column = ":".$col;
            $query->bindValue($column, $value);
        }
    }

    private function whereBindParams($query, $data)
    {
        foreach($data as $col => $value) {
            $opt = $this->findOperand($col);
            if (is_array($value) && gettype($col) == 'integer') {
                // [['a' => 'b', 'e' => 'f']['c' => 'd']]
                foreach($value as $valueKey => $valueVal) {
                    $column = ":".$valueVal;
                    $query->bindValue($column, $valueVal);
                }
            } else {
                if (is_array($value)) {
                    // ['id' => [1,2,3,4]]
                    foreach($value as $val) {
                        $column = ":".$val;
                        $query->bindValue($column, $val);
                    }
                } else {
                    // ['id' => 'pary']
                    $column = ":".$col;
                    $query->bindValue($column, $value);
                }
            }
        }
    }

    private function generateBinds($row)
    {
        $data = '(';
        $i = 1;
        foreach ($row as $col => $value) {
            $data .= ":" .  $col ;
            if ($i < count($row)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    public function pdoDirect($query, $var)
    {
        $statement = $this->getConnection()->prepare($query);

        if (count($var)) {
            $statement->execute($var);
        } else {
            $statement->execute();
        }
        
        return $statement->fetchAll();
    }

    private function generateWhere($where)
    {
        if ($where == "" || count($where) == 0) {
            return '';
        }
        $data = ' WHERE '; $i = 1;
        foreach ($where as $key => $value) {
            $opt = $this->findOperand($key);
            if (is_array($value) && gettype($key) == 'integer' ) { // [], ke yani har chi dakhelesh hast ba ham or beshan
                $data .= '( '; $j = 1;
                foreach($value as $valueKey => $valueVal) {
                    $data .= '(';
                    $data .= '('.$valueKey . $opt . ':' . $valueVal . ') ';
                    if ($j < count($value)) {
                        $data .= 'AND ';
                    }
                    $j++;
                }
                $data .= ')';
                if ($i < count($where)) {
                    $data .= 'OR ';
                }
                $i++;
            } else {
                if (is_array($value)) { // 'id' => []
                    foreach($value as $val) {
                        $data .= '('.$key . $opt . ':' . $val . ') ';
                        if ($i < count($value)) {
                            $data .= 'OR ';
                        }
                        $i++;
                    }
                } else { // 'id' => 'pm'
                    $data .= '('. '`' .$key . '`' . $opt . ':' . $key . ') ';
                    if ($i < count($where)) {
                        $data .= 'AND ';
                    }
                    $i++;
                }
            }
        }
        return $data;
    }

    public function generateUpdateSet($set)
    {
        $data = 'SET ';
        $i = 1;
        foreach($set as $col => $value){
            $data .= $col . '=' . ':' . $col;
            if ($i < count($set)) {
                $data .= ', ';
            }
            $i++;
        }
        return $data;
    }

    public function update($data)
    {
        $where = $this->generateWhere($this->getWhere());
        $set = $this->generateUpdateSet($data);
        $query = $this->createQuery('', 'UPDATE', '', $where, '', '', '', $set);
        $this->insertBindParams($query, $data);
        $this->insertBindParams($query, $this->getWhere());
        $this->execution($query);
        return $this;
    }

    public function delete()
    {
        $where = $this->generateWhere($this->getWhere());
        $query = $this->createQuery('', 'DELETE', '', $where, '', '', '', '');
        $this->insertBindParams($query, $this->getWhere());
        $this->execution($query);
        return $this;
    }

    public function select()
    {
        $data = [];
        $row = $this->generateRows($this->getRow());
        $limit = $this->generateLimit($this->getLimit());
        $where = $this->generateWhere($this->getWhere());
        $join  = $this->generateJoin($this->getJoin());
        $order = $this->generateOrder($this->getOrder());
        $group = $this->generateGroup($this->getGroup());
        $query = $this->createQuery($row, 'SELECT', $join, $where, $order, $limit, $group, '');
        $this->whereBindParams($query, $this->getWhere());
        try {
            $query->execute();
            if ($this->limit == 1) {
            $data = $query->fetch();
            } else {
                $data = $query->fetchAll();
            }
            if ($data === false || is_null($data)) {
                $data = [];
            }
            $this->setResult($data);
        } catch (\PDOException $e) {
            http_response_code(400);
            $this->setConnectionError($e);
        }
        return $this;
    }

    private function createQuery($row, $crud, $join = '', $where = '', $order = '', $limit = '', $group = '', $set = '')
    {
        $statement  = '';
        switch ($crud) {
            case 'SELECT':
                $statement = "SELECT ". $row ." FROM " . '`' . $this->getTable() . '`' . $join . $where . $order . $limit . $group;
                break;
            case 'UPDATE':
                $statement = "UPDATE " . '`' . $this->getTable() .'` '.  $set . $where;
                break;
            case 'DELETE':
                $statement = "DELETE FROM " . '`' .  $this->getTable() . '`' . $where;
                break;
            default:
                break;
        }
        $query = $this->getConnection()->prepare($statement);
        $this->setQuery($statement);
        return $query;
    }
}