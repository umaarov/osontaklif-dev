<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$controller = new App\Http\Controllers\UserController();
$controller->edit();