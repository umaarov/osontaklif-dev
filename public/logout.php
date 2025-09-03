<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

$controller = new App\Http\Controllers\AuthController();
$controller->logout();