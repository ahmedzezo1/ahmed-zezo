<?php
namespace App\Core;

declare(strict_types=1);

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $driver = Config::get('database.driver', 'sqlite');
        try {
            if ($driver === 'mysql') {
                $host = Config::get('database.host', '127.0.0.1');
                $port = (int)Config::get('database.port', 3306);
                $dbname = Config::get('database.database', 'it_assets');
                $username = Config::get('database.username', 'root');
                $password = Config::get('database.password', '');
                $charset = Config::get('database.charset', 'utf8mb4');
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
                self::$pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } else {
                $dbPath = STORAGE_PATH . '/database/app.sqlite';
                // Ensure directory exists
                @mkdir(dirname($dbPath), 0777, true);
                $dsn = 'sqlite:' . $dbPath;
                self::$pdo = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Database connection error: ' . $e->getMessage();
            exit;
        }

        return self::$pdo;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::prepare($sql, $params);
        return $stmt->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::prepare($sql, $params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::prepare($sql, $params);
        return $stmt->rowCount();
    }

    public static function insert(string $sql, array $params = []): int
    {
        $stmt = self::prepare($sql, $params);
        $pdo = self::pdo();
        $stmt->execute();
        return (int)$pdo->lastInsertId();
    }

    private static function prepare(string $sql, array $params): \PDOStatement
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $paramKey = is_int($key) ? $key + 1 : (str_starts_with((string)$key, ':') ? (string)$key : ':' . $key);
            $stmt->bindValue($paramKey, $value);
        }
        $stmt->execute();
        return $stmt;
    }
}
