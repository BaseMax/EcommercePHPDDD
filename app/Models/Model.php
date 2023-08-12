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
        $sql = "UPDATE ".$this->getTableName()." SET ";
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
        $sql .= " WHERE id = ".$this->$id;
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

    private function getCustomTableName($modelObject){
        $classPath = explode('\\', get_class($modelObject));
        return lcfirst($classPath[count($classPath) - 1]).'s';
    }

    private function getTableName(){
        return $this->getCustomTableName($this);
    }

    public function hasMany($model, $key){
        $id = 'id';
        return (new $model)->where($key, $this->$id)->get();
    }

    public function belogsTo($model, $key){
        return (new $model)->where('id', $this->$key)->get()[0];
    }

    public function whereIn($col, $vals){
        if(count($vals) == 0) return;
        $this->sql .= " WHERE $col IN (";
        if(is_string($vals[0])){
            foreach($vals as $val){
                $this->sql .= "'$val',";
            }
        } else {
            foreach($vals as $val){
                $this->sql .= "$val,";
            }
        }
        $this->sql = substr($this->sql, 0, -1);
        $this->sql .= ")";
        return $this;
    }

    public function getBelogsToMany($model, $table, $mKey, $rKey, $pivot = []){
        $id = 'id';
        $rTable = $this->getCustomTableName(new $model);
        $sql = "SELECT * FROM $rTable INNER JOIN $table ON ".$rTable.".id = ".$table.".".$rKey." where ".$table.".".$mKey." = ".$this->$id;
        $this->db->execute($sql);
        $result = $this->db->statement->fetchAll();
        $rObjects = [];
        $cols = (new $model)->cols;
        foreach($result as $r){
            $object = new $model;
            $object = (array) $object;
            foreach($cols as $col){
                if($col == 'id') {
                    $object[$col] = $r[$rKey];
                } else {
                    $object[$col] = $r[$col];
                }
            }
            foreach($pivot as $p){
                $object[$p] = $r[$p];
            }
            $object = (object) $object;
            array_push($rObjects, $object);
        }
        return $rObjects;
    }

    public function setBelogsToMany($data, $model, $table, $mKey, $rKey, $pivot = []){
        $id = 'id';
        $sql = "INSERT INTO $table($mKey, $rKey,";
        foreach($pivot as $p){
            $sql .= $p.',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ") VALUES";
        $rName = substr($this->getCustomTableName((new $model)), 0, -1);
        foreach($data as $d){
            $sql .= "(";
            $sql .= $this->$id.','.$d[$rName]->$id.',';
            foreach($pivot as $p){
                if(is_string($d[$p])){
                    $sql .= "'".$d[$p]."',";
                } else {
                    $sql .= $d[$p].',';
                }
            }
            $sql = substr($sql, 0, -1);
            $sql .= "),";
        }
        $sql = substr($sql, 0, -1);
        $this->db->execute($sql);
    }
}