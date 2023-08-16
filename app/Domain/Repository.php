<?php

namespace App\Domain;

use App\Infrastructure\Database\IDatabase;

abstract class Repository implements IRepository
{
    public $modelClass;
    public array $cols;

    private IDatabase $db;

    public function __construct(IModel $modelClass, string $db){
        $this->db = new $db($this);
        $this->modelClass = $modelClass;
        $this->cols = $modelClass->getCols();
    }

    public function create(array $vals) : int {
        return $this->db->create($vals);
    }
    
    public function get() : array {
        return $this->db->get();
    }

    public function first() : IModel {
        return $this->db->first();
    }

    public function count() : int {
        return $this->db->count();
    }

    public function update(IModel $modelObject) : void {
        $this->db->update($modelObject);
    }

    public function delete(int $objectId) : void {
        $this->db->delete($objectId);
    }

    public function where(string $col, mixed $val) : Repository {
        return $this->db->where($col, $val);
    }

    public function whereIn(string $col, array $vals) : Repository {
        return $this->db->whereIn($col, $vals);
    }

    public function getHasMany(int $modelId, string $modelRepository, string $key) : array {
        return $this->db->getHasMany($modelId, $modelRepository, $key);
    }

    public function getBelogsTo(int $modelId, string $modelRepository, string $key) : IModel {
        return $this->db->getBelogsTo($modelId, $modelRepository, $key);
    }

    public function getBelogsToMany(int $modelId, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : array {
        return $this->db->getBelogsToMany($modelId, $modelRepository, $table, $mKey, $rKey, $pivot);
    }

    public function setBelogsToMany(int $modelId, array $data, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : void {
        $this->db->setBelogsToMany($modelId, $data, $modelRepository, $table, $mKey, $rKey, $pivot);
    }
}