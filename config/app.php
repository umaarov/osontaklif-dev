<?php

define('APP_NAME', getenv('APP_NAME') ?: 'OsonTaklif');
const APP_LOCALE = 'uz';

spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

function asset($path)
{
    return APP_URL . '/' . ltrim($path, '/');
}

function route($name, $params = [])
{
    global $routes;
    $url = $routes[$name] ?? '/';
    foreach ($params as $key => $value) {
        $url = str_replace('{' . $key . '}', $value, $url);
    }
    return APP_URL . $url;
}

$lang = APP_LOCALE;
$lang_file = BASE_PATH . "/resources/lang/{$lang}.json";
$translations = [];
if (file_exists($lang_file)) {
    $translations = json_decode(file_get_contents($lang_file), true);
}

function __($key)
{
    global $translations;
    return $translations[$key] ?? $key;
}