<?php

namespace App\Infrastructure\Database;

use App\Domain\IModel;
use App\Domain\Repository;

interface IDatabase
{
    public function create(array $vals) : int;
    public function get() : array;
    public function first() : IModel;
    public function count() : int;
    public function update(IModel $modelObject) : void;
    public function delete(int $objectId) : void;
    public function where(string $col, mixed $val) : Repository;
    public function whereIn(string $col, array $vals) : Repository;
    public function getHasMany(int $modelId, string $modelRepository, string $key) : array;
    public function getBelogsTo(int $modelId, string $modelRepository, string $key) : IModel;
    public function getBelogsToMany(int $modelId, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : array;
    public function setBelogsToMany(int $modelId, array $data, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : void;
}