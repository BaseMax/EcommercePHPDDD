<?php

namespace App\Models;

use App\Database\Database;

abstract class Model
{
    protected $cols;

    private $sql;
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function create($vals){
        $tableName = $this->getTableName();
        $cols = $this->cols;
        $sql = "INSERT INTO ".$tableName."(";
        foreach($cols as $col){
            if($col == 'id') continue;
            $sql .= $col.',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ') VALUES(';
        foreach($cols as $col){
            if($col == 'id') continue;
            if(is_string($vals[$col])){
                $sql .= "'".$vals[$col]."',";
            } else {
                $sql .= $vals[$col].',';
            }
        }
        $sql = substr($sql, 0, -1);
        $sql .= ');';
        $this->db->execute($sql);
        return $this->db->connection->lastInsertId();
    }
    
    public function get(){
        $this->sql = "SELECT * FROM ".$this->getTableName().$this->sql;
        $this->db->execute($this->sql);
        $result = $this->db->statement->fetchAll();
        $objects = [];
        $className = get_class($this);
        $cols = $this->cols;
        foreach($result as $r){
            $object = new $className;
            foreach($cols as $col){
                $object->$col = $r[$col];
            }
            array_push($objects, $object);
        }
        return $objects;
    }

    public function count(){
        $this->sql = "SELECT COUNT(*) FROM ".$this->getTableName().$this->sql;
        $this->db->execute($this->sql);
        $result = $this->db->statement->fetch();
        return $result['COUNT(*)'];
    }

    public function update(){
        $cols = $this->cols;
        $sql = "UPDATE ".$this->getTableName()." SET";
        foreach($cols as $col){
            if($col == 'id') continue;
            if(is_string($this->$col)){
                $sql .= $col." = '".$this->$col."',";
            } else {
                $sql .= $col.' = '.$this->$col.',';
            }
        }
        $sql = substr($sql, 0, -1);
        $id = 'id';
        $sql .= "WHERE id = ".$this->$id;
        $this->db->execute($sql);
    }

    public function delete(){
        $id = 'id';
        $sql = "DELETE FROM ".$this->getTableName()." WHERE id = ".$this->$id;
        $this->db->execute($sql);
    }

    public function where($col, $val){
        if(is_string($val)){
            $this->sql .= " WHERE $col = '$val'";
        } else {
            $this->sql .= " WHERE $col = $val";
        }
        return $this;
    }

    private function getTableName(){
        $classPath = explode('\\', get_class($this));
        return lcfirst($classPath[count($classPath) - 1]).'s';
    }

    public function hasMany($model, $key){
        $id = 'id';
        return (new $model)->where($key, $this->$id)->get();
    }

    public function belogsTo($model, $key){
        return (new $model)->where('id', $this->$key)->get()[0];
    }
}