<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$appEnv = getenv('APP_ENV') ?: 'production';
$appDebug = filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN);

define('APP_ENV', $appEnv);
define('APP_URL', getenv('APP_URL'));

if ($appEnv === 'production' && !$appDebug) {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/storage/logs/php_errors.log');
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/database.php';
require_once BASE_PATH . '/Core/helpers.php';