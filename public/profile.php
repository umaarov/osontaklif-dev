<?php

use Core\AuthService;

require_once __DIR__ . '/../config/bootstrap_secure.php';

$username = $_GET['user'] ?? null;

if (!$username) {
    $user = AuthService::user();
    if ($user) {
        header('Location: profile.php?user=' . $user->username);
        exit();
    }
    header("Location: login.php");
    exit();
}

$controller = new App\Http\Controllers\UserController();
$controller->show($username);