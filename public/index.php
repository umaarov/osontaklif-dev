<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/bootstrap_secure.php';

$routes = [
    'GET' => [
        'sitemap.xml' => 'PageController@sitemap',
        '/' => 'PageController@home',
        'index.php' => 'PageController@home',
        'home.php' => 'PageController@home',
        'login.php' => 'AuthController@showLoginForm',
        'register.php' => 'AuthController@showRegisterForm',
        'logout.php' => 'AuthController@logout',
        'professions/{slug}' => 'PageController@profession',
        'questions/{id}/{professionSlug}' => 'PageController@question',
        'mock.php' => 'PageController@mock',
        'requirements.php' => 'PageController@requirements',
        'requirements/{slug}' => 'PageController@requirement_show',
        'about.php' => 'PageController@about',
        'terms.php' => 'PageController@terms',
        'sponsorship.php' => 'PageController@sponsorship',
        'ads.php' => 'PageController@ads',
    ],
    'POST' => [
        'login.php' => 'AuthController@login',
        'register.php' => 'AuthController@register',
        'questions/{id}/answer' => 'AnswerController@store',
    ]
];

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

$base_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($base_dir === '/') $base_dir = '';
$route = str_replace($base_dir, '', $request_uri);
$route = trim($route, '/');
if (empty($route)) {
    $route = '/';
}

$controllerName = null;
$methodName = null;
$params = [];

if (isset($routes[$request_method])) {
    foreach ($routes[$request_method] as $routePattern => $handler) {
        $pattern = trim($routePattern, '/');
        $routePatternRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);

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
}

if ($controllerName && $methodName) {
    $controllerClass = 'App\\Http\\Controllers\\' . $controllerName;
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo "404 - Method Not Found in Controller";
        }
    } else {
        http_response_code(404);
        echo "404 - Controller Not Found";
    }
} else {
    http_response_code(404);
    require BASE_PATH . '/resources/views/errors/404.php';
}