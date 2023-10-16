<?php

namespace MoviesApi\App;

use PDO;
final class Database
{
    public ?PDO $conn = null;

    public function __construct()
    {
        if ($this->conn == null) {
            $host = $_ENV['DB_HOST'];
            $dbName = $_ENV['DB_NAME'];
            $userName = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASSWORD'];
            $dsn = "mysql:host=$host;dbname=$dbName";
            $option = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, $userName, $pass, $option);
        }
    }
}