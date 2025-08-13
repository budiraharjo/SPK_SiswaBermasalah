<?php

namespace Core;

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            $config = require __DIR__ . '/../config/config.php';
            $db = $config['db'];

            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            
            try {
                self::$pdo = new \PDO($dsn, $db['user'], $db['pass'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]);
            } catch (\PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
