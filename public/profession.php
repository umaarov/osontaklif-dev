<?php
require_once __DIR__ . '/../config/bootstrap.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header("Location: home.php");
    exit();
}

$controller = new App\Http\Controllers\PageController();
$controller->profession($slug);
