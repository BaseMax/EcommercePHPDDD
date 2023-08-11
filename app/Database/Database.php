<?php

namespace App\Database;

use PDO;

class Database
{
    public $connection;
    public $statement;

    public function __construct()
    {
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

    public function execute($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
    }
}