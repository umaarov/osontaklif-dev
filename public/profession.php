<?php
require_once __DIR__ . '/../config/bootstrap.php';

$name = $_GET['name'] ?? null;

if (!$name) {
    header("Location: home.php");
    exit();
}

$controller = new App\Http\Controllers\PageController();
$controller->profession($name);