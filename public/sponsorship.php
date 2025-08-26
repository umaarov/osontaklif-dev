<?php
require_once __DIR__ . '/../config/bootstrap.php';
$controller = new App\Http\Controllers\PageController();
$controller->sponsorship();
