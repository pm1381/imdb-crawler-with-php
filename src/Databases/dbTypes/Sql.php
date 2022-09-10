<?php

namespace App\Databases\DbTypes;

use App\Databases\Db\Db;
use App\Helpers\Arrays;

class Sql extends Db
{

    private $table;
    private $queryResult;

    public function __construct(){}

    public function setTable($table)
    {
        $this->table = $table;
    }

    protected function generateInsertRows($data)
    {
        $keys = [];
        foreach($data as $key=>$value) {
            $keys[] = $key;
        }
        $data = '(';
        $i = 1;
        foreach ($keys as $col) {
            $data .= '`' .  $col . '`' ;
            if ($i < count($keys)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    protected function generateOrder($order)
    {
        $data = '';
        if (count($order) > 0) {
            $data = ' ORDER BY ';
            foreach ($order as $key => $type)
            {
                $type = strtoupper($type);
                $data .= $key . '=' . $type . " ";
            }
        }
        return $data;
    }

    protected function generateGroup($group)
    {
        $data = '';
        $i = 1;
        if (count($group)) {
            $data = ' GROUP BY ';
        }
        foreach ($group as $value) {
            $data .= $value;
            if ($i < count($group)) {
                $data .= ',';
            }
        }
        return $data;
    }

    protected function generateJoin($join)
    {
        $data = '';
        foreach ($join as $secondTable => $columns) {
            foreach($columns as $key => $value) {
                $data .= " LEFT JOIN " . '`' . $secondTable . '`' .  ' ON ' . '`'.$key.'`' . ' = ' .'`'.$value.'`';
            }
        }
        return $data;
    }

    protected function findOperand(&$key)
    {
        $opt = '=';
        if (strpos($key, "LIKE") !== false  &&  strpos($key, "NOTLIKE") === false) {
            $key = trim(explode("LIKE", $key)[0]);
            $opt = ' like ';
        }
        if (strpos("!=", $key) !== false) {
            $opt = ' != ';
            $key = trim(explode("!=", $key)[0]);
        }
        if (strpos($key, "NOTLIKE") !== false) {
            $key = trim(explode("NOTLIKE", $key)[0]);
            $opt = ' not like ';
        }
        return $opt;
    }

    protected function manageSpecialRows($col, $special)
    {
        preg_match("/$special\((.*)\)/", $col, $matches);
        $newCol = '`' . $matches[1] . '`';
        if ($matches[1] == "*") {
            $newCol = $matches[1];
        }
        return $special . '(' . $newCol . ')';
    }

    protected function generateRows($row)
    {
        $specials = Arrays::specialCols();
        if (count($row) > 0) {
            $data = ''; $i = 1;$control = 0;
            foreach ($row as $col) {
                foreach ($specials as $spec) {
                    if (strpos($col, $spec) !== false) {
                        $data .= $this->manageSpecialRows($col, $spec);
                        $control = 1;
                        break;
                    }
                }
                if ($control == 0) {
                    if (strpos($col, "->") !== false) {
                        list($previousName, $newName) = explode("->", $col);
                        $data .= '`' . $previousName . '`' . 'AS' . '`' . $newName . '`';
                    } else {
                        $data .=  '`' . $col . '`' ;
                    }
                }
                if ($i < count($row)) {
                    $data .= ", ";
                }
                $i++;
                $control = 0;
            }
            return $data;
        }
        return '*';
    }

    protected function generateLimit($limit)
    {
        if (count($this->getLimit()) <= 0) {
            return '';
        } 
        $data = ' LIMIT ';
        if (is_array($limit)) {
            $data .= $limit[0] . " OFFSET " . $limit[1] ;
        } else {
            $data .= $limit . ' ';
        }
        if ($limit == 0) {
            return '';
        }
        return $data;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function fetchResult()
    {
        return $this->queryResult;
    }

    public function setResult($res)
    {
        $this->queryResult = $res;
    }

    public function getCount()
    {
        return count($this->queryResult);
    }
}
