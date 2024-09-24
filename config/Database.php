<?php

namespace Config;

require_once('config.php');

use PDO;
use PDOException;
use Exception;

class Database
{
    private $pdo;
    public function __construct()
    {
        try {
            $SQL ="mysql:host=".getenv('DB_HOST').";dbname=".getenv('DB_NAME');

            $this->pdo = new PDO($SQL, getenv('DB_USERNAME'),getenv('DB_PASSWORD'));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("Connection failed. Please contact support.");
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}


