<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($base_dir === '/') $base_dir = '';
$route = str_replace($base_dir, '', $request_uri);
$route = trim($route, '/');
if (empty($route)) {
    $route = 'home';
}

$routes = [
    'home' => 'PageController@home',
    'professions/{slug}' => 'PageController@profession',
    'questions/{id}/{professionSlug}' => 'PageController@question',
    'mock' => 'PageController@mock',
    'requirements' => 'PageController@requirements',
    'requirements/{slug}' => 'PageController@requirement_show',
    'about' => 'PageController@about',
    'terms' => 'PageController@terms',
    'sponsorship' => 'PageController@sponsorship',
    'ads' => 'PageController@ads',
];

$controllerName = null;
$methodName = null;
$params = [];

foreach ($routes as $routePattern => $handler) {
    $routePatternRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePattern);
    if (preg_match('#^' . $routePatternRegex . '$#', $route, $matches)) {
        list($controllerName, $methodName) = explode('@', $handler);
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        break;
    }
}


if ($controllerName && $methodName) {
    $controllerClass = 'App\\Http\\Controllers\\' . $controllerName;
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo "404 - Method Not Found";
        }
    } else {
        http_response_code(404);
        echo "404 - Controller Not Found";
    }
} else {
    http_response_code(404);
    require BASE_PATH . '/resources/views/errors/404.php';
}
