<?php
define('DB_HOST', getenv('DB_HOST'));
define('DB_PORT', getenv('DB_PORT'));
define('DB_DATABASE', getenv('DB_DATABASE'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));

class Database
{
    private static ?PDO $conn = null;
    private static ?Database $instance = null;

    private function __construct()
    {
        if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST'));
        if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT'));
        if (!defined('DB_DATABASE')) define('DB_DATABASE', getenv('DB_DATABASE'));
        if (!defined('DB_USERNAME')) define('DB_USERNAME', getenv('DB_USERNAME'));
        if (!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASSWORD'));

        $dbSocket = getenv('DB_SOCKET');

        if ($dbSocket) {
            $dsn = 'mysql:unix_socket=' . $dbSocket . ';dbname=' . DB_DATABASE . ';charset=utf8mb4';
        } else {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE . ';charset=utf8mb4';
        }

        try {
            self::$conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
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