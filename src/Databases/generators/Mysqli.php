<?php

namespace App\Databases\Generators;

use App\Databases\DbTypes\Sql;
use App\interfaces\Generator;

class Mysqli extends Sql implements Generator
{
    private $connection;
    private $connectionError = ""; 

    public function __construct(){}   

    public function connect()
    {
        $this->connection = new mysqli('localhost', 'root', '', 'test');
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

    public function execution($query){
        if (! mysqli_query($this->getConnection(), $query)) {
            $this->setConnectionError(mysqli_error($this->getConnection()));
        }
    }

    public function select(){
        $row = $this->generateRows($this->getRow());
        $join = $this->generateJoin($this->getJoin());
        $group = $this->generateGroup($this->getGroup());
        $limit = $this->generateLimit($this->getLimit());
        $order = $this->generateOrder($this->getOrder());
        $where = $this->generateWhere($this->getWhere());
        $sql    =   'SELECT ' . $row . '  FROM ' . '`' . $this->getTable() . $join . $where . $order . $limit . $group;
        $result   =   mysqli_query($this->getConnection(), $sql);
        if ($result != false) {
            if (mysqli_num_rows($result)) {
                $this->setResult(mysqli_fetch_all($result, MYSQLI_ASSOC));
            } else {
                $this->setConnectionError("no record found");
            }
        } else {
            $this->setConnectionError(mysqli_error($this->getConnection()));
        }
        return $this;
    }

    private function generateWhere($where) {
        if ($where == "" || count($where) == 0) {
            return '';
        }
        $data = ' WHERE '; $i = 1;
        foreach ($where as $key => $value) {
            $opt = $this->findOperand($key);
            if (is_array($value) && gettype($key) == 'integer' ) {
                $data .= '( '; $j = 1;
                foreach($value as $valueKey => $valueVal) {
                    $data .= '(';
                    $data .= '('. '`' . $valueKey . '`' . $opt . '"' . $valueVal . '"' . ') ';
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
                if (is_array($value)) {
                    foreach($value as $val) {
                        $data .= '('. '`' . $key . '`' . $opt . '"' .  $val . '"' . ') ';
                        if ($i < count($value)) {
                            $data .= 'OR ';
                        }
                        $i++;
                    }
                } else {
                    $data .= '('. '`' . $key . '`' . $opt . '"' . $value . '"' . ') ';
                    if ($i < count($where)) {
                        $data .= 'AND ';
                    }
                    $i++;
                }
            }
        }
        return $data;
        // '`' . ' WHERE `username` = ' . '"' . "$username" . '"' . ' AND `password` = '
    }

    private function generateUpdateSet($set)
    {
        //SET lastname='Doe'
        $data = 'SET ';
        $i = 1;
        foreach($set as $col => $value){
            $data .= $col . '=' . '"' . $col . '"';
            if ($i < count($set)) {
                $data .= ', ';
            }
            $i++;
        }
        return $data;
    }

    public function update($data) {
        $where = $this->generateWhere($this->getWhere());
        $set = $this->generateUpdateSet($data);
        $sql = "UPDATE " . '`' . $this->getTable() .'` '.  $set . $where;
        $this->execution($sql);
        return $this;
    }

    public function delete() {
        $where = $this->generateWhere($this->getWhere());
        $sql = "DELETE FROM " . '`' .  $this->getTable() . '`' . $where; 
        $this->execution($sql);
        return $this;
    }

    public function insert($data){
        $cols = $this->generateInsertRows($data);
        $values = $this->generateValues($data);
        $sql = "INSERT INTO " . '`' . $this->getTable() . '`' . $cols ." VALUES " . $values;
        $this->execution($sql);
        return $this;
    }

    private function generateValues($data) {
        $keys = [];
        foreach($data as $key=>$value) {
            $keys[] = $value;
        }
        $data = '(';
        $i = 1;
        foreach ($keys as $col) {
            $data .= "'" .  $col . "'" ;
            if ($i < count($keys)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }
}

