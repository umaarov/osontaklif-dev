<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$controller = new App\Http\Controllers\ExperienceController();
$controller->store();