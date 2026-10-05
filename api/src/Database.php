<?php

namespace App;
use PDO;

class Database {
    private PDO $pdo;

    public function __construct(string $host, string $user, string $password) {
        $this->pdo = new PDO($host, $user, $password);;
    }

    public function transaction(callable $callback) {
        $this->pdo->beginTransaction();

        try {
            $result = $callback($this->pdo);
            $this->pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e; 
        }
    }

    
}
