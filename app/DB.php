<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

final class DB
{
    private static ?PDO $connection = null;

    public static function connect(array $config): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['name']
        );

        try {
            self::$connection = new PDO($dsn, $config['user'], $config['password'], [
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Connection failed: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }

        return self::$connection;
    }
}
