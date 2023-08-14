<?php

namespace App\Domain;

abstract class Repository implements IRepository
{
    public $modelClass;
    public $cols;

    private $db;

    public function __construct($modelClass, $db){
        $this->db = new $db($this);
        $this->modelClass = $modelClass;
        $this->cols = $modelClass->cols;
    }

    public function create($vals){
        return $this->db->create($vals);
    }
    
    public function get(){
        return $this->db->get();
    }

    public function first(){
        return $this->db->first();
    }

    public function count(){
        return $this->db->count();
    }

    public function update($modelObject){
        $this->db->update($modelObject);
    }

    public function delete($objectId){
        $this->db->delete($objectId);
    }

    public function where($col, $val){
        return $this->db->where($col, $val);
    }

    public function whereIn($col, $vals){
        return $this->db->whereIn($col, $vals);
    }

    public function getHasMany($modelId, $modelRepository, $key){
        return $this->db->getHasMany($modelId, $modelRepository, $key);
    }

    public function getBelogsTo($modelId, $modelRepository, $key){
        return $this->db->getBelogsTo($modelId, $modelRepository, $key);
    }

    public function getBelogsToMany($modelId, $modelRepository, $table, $mKey, $rKey, $pivot = []){
        return $this->db->getBelogsToMany($modelId, $modelRepository, $table, $mKey, $rKey, $pivot);
    }

    public function setBelogsToMany($modelId, $data, $modelRepository, $table, $mKey, $rKey, $pivot = []){
        $this->db->setBelogsToMany($modelId, $data, $modelRepository, $table, $mKey, $rKey, $pivot);
    }
}