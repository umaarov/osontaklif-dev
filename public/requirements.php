<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

$name = $_GET['name'] ?? null;

$controller = new App\Http\Controllers\PageController();
if ($name) {
    $controller->requirement_show($name);
} else {
    $controller->requirements();
}