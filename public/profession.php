<?php
require_once __DIR__ . '/../config/bootstrap.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: home.php");
    exit();
}

$controller = new App\Http\Controllers\PageController();
$controller->profession($id);