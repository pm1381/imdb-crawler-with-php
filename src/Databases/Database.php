<?php

namespace App\Databases;

use App\interfaces\Generator as InterfacesGenerator;

class Database {
    private $dbError = false;
    public static $selectedDb = null;
    
    public function databaseConnection(InterfacesGenerator $selectedDb) {
         if (!self::$selectedDb) {
            self::$selectedDb = $selectedDb; 
            self::$selectedDb->connect();
        } else {
            echo 'cannot connect to database';
            $this->setDbError(true);
        }
    }

    public function showSelectedDb()
    {
        return self::$selectedDb;
    }

    public function manageSelectQuery($row=[], $join=[],$where=[], $order=[], $limit=[], $group=[])
    {
        return self::$selectedDb->row($row)->join($join)->where($where)->order($order)->limit($limit)->group($group)->select();
    }

    public function manageDeleteQuery($where = [])
    {
        return self::$selectedDb->where($where)->delete();
    }

    public function manageUpdateQuery($update, $where=[])
    {
        return self::$selectedDb->where($where)->update($update);
    }

    public function manageInsertQuery($data)
    {
        return self::$selectedDb->insert($data);
    }
 
    public function getDbError()
    {
        return $this->dbError;
    }

    private function setDbError($boolean)
    {
        $this->dbError = $boolean;
    }
}