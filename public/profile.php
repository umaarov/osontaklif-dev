<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

$userId = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: login.php");
    exit();
}

$controller = new App\Http\Controllers\UserController();
$controller->show($userId);