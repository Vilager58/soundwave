<?php

namespace App;
use PDO;

class Database {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
