<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

$controller = new App\Http\Controllers\AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->showLoginForm();
}