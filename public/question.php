<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

$id = $_GET['id'] ?? null;
$professionId = $_GET['pid'] ?? null;

if (!$id) {
    header("Location: home.php");
    exit();
}

$controller = new App\Http\Controllers\PageController();
$controller->question($id, $professionId);