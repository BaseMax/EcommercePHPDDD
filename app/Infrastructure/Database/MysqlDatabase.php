<?php

namespace App\Infrastructure\Database;

use App\Presentation\Http\Response\Response;
use App\Domain\IModel;
use App\Domain\Repository;
use PDO;

class MysqlDatabase implements IDatabase
{
    private $connection;
    private $statement;
    private $repository;
    private $sql;

    public function __construct($repository)
    {
        $this->repository = $repository;
        $dsn = 'mysql:' . http_build_query([
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'dbname' => $_ENV['DB_DATABASE'],
            'charset' => 'utf8mb4'
        ], '', ';');
        $this->connection = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    private function execute($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
    }

    public function create(array $vals) : int{
        $tableName = $this->getTableName();
        $cols = $this->repository->cols;
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
        $this->execute($sql);
        return $this->connection->lastInsertId();
    }

    public function get() : array{
        $this->sql = "SELECT * FROM ".$this->getTableName().$this->sql;
        try{
            $this->execute($this->sql);
        } catch(\Exception $e){
            Response::json(['sql' => $this->sql, 'e' => (array) $e]);
        }
        $result = $this->statement->fetchAll();
        $objects = [];
        $className = get_class($this->repository->modelClass);
        $cols = $this->repository->cols;
        foreach($result as $r){
            $object = new $className;
            foreach($cols as $col){
                $object->$col = $r[$col];
            }
            array_push($objects, $object);
        }
        $this->sql = "";
        return $objects;
    }

    public function first() : IModel{
        $objects = $this->get();
        if(count($objects) > 0){
            return $objects[0];
        }
        return null;
    }

    public function count() : int{
        $this->sql = "SELECT COUNT(*) FROM ".$this->getTableName().$this->sql;
        $this->execute($this->sql);
        $result = $this->statement->fetch();
        $this->sql = "";
        return $result['COUNT(*)'];
    }

    public function update(IModel $modelObject) : void{
        $this->repository->modelClass = $modelObject;
        $cols = $this->repository->cols;
        $sql = "UPDATE ".$this->getTableName()." SET ";
        foreach($cols as $col){
            if($col == 'id') continue;
            if(is_string($this->repository->modelClass->$col)){
                $sql .= $col." = '".$this->repository->modelClass->$col."',";
            } else {
                $sql .= $col.' = '.$this->repository->modelClass->$col.',';
            }
        }
        $sql = substr($sql, 0, -1);
        $id = 'id';
        $sql .= " WHERE id = ".$this->repository->modelClass->$id;
        $this->execute($sql);
    }

    public function delete(int $objectId) : void{
        $id = 'id';
        $sql = "DELETE FROM ".$this->getTableName()." WHERE id = ".$objectId;
        $this->execute($sql);
    }

    public function where(string $col, mixed $val) : Repository{
        if(is_string($val)){
            $this->sql .= " WHERE $col = '$val'";
        } else {
            $this->sql .= " WHERE $col = $val";
        }
        return $this->repository;
    }

    private function getCustomTableName($modelObject){
        $classPath = explode('\\', get_class($modelObject));
        return lcfirst($classPath[count($classPath) - 1]).'s';
    }

    private function getTableName(){
        return $this->getCustomTableName($this->repository->modelClass);
    }

    public function whereIn(string $col, array $vals) : Repository{
        if(count($vals) == 0) return $this->repository;
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
        return $this->repository;
    }

    private function setModelObjectBeforeForigenActions($modelId){
        $this->sql = "";
        $this->repository->modelClass = $this->where('id', $modelId)->first();
    }

    public function getHasMany(int $modelId, string $modelRepository, string $key) : array{
        $this->setModelObjectBeforeForigenActions($modelId);
        $id = 'id';
        return (new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->where($key, $this->repository->modelClass->$id)->get();
    }

    public function getBelogsTo(int $modelId, string $modelRepository, string $key) : IModel{
        $this->setModelObjectBeforeForigenActions($modelId);
        return (new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->where('id', $this->repository->modelClass->$key)->first();
    }

    public function getBelogsToMany(int $modelId, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : array{
        $this->setModelObjectBeforeForigenActions($modelId);
        $id = 'id';
        $rTable = $this->getCustomTableName((new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->modelClass);
        $sql = "SELECT * FROM $rTable INNER JOIN $table ON ".$rTable.".id = ".$table.".".$rKey." where ".$table.".".$mKey." = ".$this->repository->modelClass->$id;
        $this->execute($sql);
        $result = $this->statement->fetchAll();
        $rObjects = [];
        $cols = (new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->cols;
        foreach($result as $r){
            $object = (new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->modelClass;
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

    public function setBelogsToMany(int $modelId, array $data, string $modelRepository, string $table, string $mKey, string $rKey, array $pivot = []) : void{
        $this->setModelObjectBeforeForigenActions($modelId);
        $id = 'id';
        $sql = "INSERT INTO $table($mKey, $rKey,";
        foreach($pivot as $p){
            $sql .= $p.',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ") VALUES";
        $rName = substr($this->getCustomTableName((new $modelRepository('App\Infrastructure\Database\MysqlDatabase'))->modelClass), 0, -1);
        foreach($data as $d){
            $sql .= "(";
            $sql .= $this->repository->modelClass->$id.','.$d[$rName]->$id.',';
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
        $this->execute($sql);
    }
}