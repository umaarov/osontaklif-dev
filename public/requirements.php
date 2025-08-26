<?php
require_once __DIR__ . '/../config/bootstrap.php';
$slug = $_GET['slug'] ?? null;
$controller = new App\Http\Controllers\PageController();
if ($slug) {
    $controller->requirement_show($slug);
} else {
    $controller->requirements();
}