<?php

namespace App\Infrastructure\Database;

interface IDatabase
{
    public function create($vals);
    public function get();
    public function first();
    public function count();
    public function update($modelObject);
    public function delete();
    public function where($col, $val);
    public function whereIn($col, $vals);
    public function getHasMany($modelId, $modelRepository, $key);
    public function getBelogsTo($modelId, $modelRepository, $key);
    public function getBelogsToMany($modelId, $modelRepository, $table, $mKey, $rKey, $pivot = []);
    public function setBelogsToMany($modelId, $data, $modelRepository, $table, $mKey, $rKey, $pivot = []);
}