<?php
require_once __DIR__ . '/../config/bootstrap.php';

$id = $_GET['id'] ?? null;

$controller = new App\Http\Controllers\PageController();
if ($id) {
    $controller->requirement_show($id);
} else {
    $controller->requirements();
}