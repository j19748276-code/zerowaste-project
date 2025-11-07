<?php

class Database {
    
    private static $pdo = null;

    public static function getInstance(): PDO
    {
        if (self::$pdo === null) {
            $host = 'localhost';
            $port = 8889;
            $dbname = 'zerowaste_db';
            $user = 'root';
            $pass = 'root';

            try {
                self::$pdo = new PDO(
                    "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8", 
                    $user, 
                    $pass
                );
                
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
            }
        }
        
        return self::$pdo;
    }
}