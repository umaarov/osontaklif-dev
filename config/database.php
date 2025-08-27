<?php

class Database
{
    private static ?PDO $conn = null;
    private static ?Database $instance = null;

    private function __construct()
    {
        $dbSocket = $_ENV['DB_SOCKET'] ?? null;
        $dbHost = $_ENV['DB_HOST'] ?? null;
        $dbPort = $_ENV['DB_PORT'] ?? 3306;
        $dbName = $_ENV['DB_DATABASE'] ?? null;
        $dbUser = $_ENV['DB_USERNAME'] ?? null;
        $dbPass = $_ENV['DB_PASSWORD'] ?? null;

        if ($dbSocket) {
            $dsn = 'mysql:unix_socket=' . $dbSocket . ';dbname=' . $dbName . ';charset=utf8mb4';
        } else {
            $dsn = 'mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName . ';charset=utf8mb4';
        }

        try {
            self::$conn = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            $errorMessage = "Connection failed: " . $e->getMessage();
            if (empty($dbUser)) {
                $errorMessage .= " (CRITICAL: DB_USERNAME is empty. Check your .env file is being loaded correctly before the database is called.)";
            }
            die($errorMessage);
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return self::$conn;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}
