<?php
require_once __DIR__ . '/../config/bootstrap.php';
$id = $_GET['id'] ?? null;
$professionSlug = $_GET['professionSlug'] ?? null;
if (!$id) { header("Location: home.php"); exit(); }
$controller = new App\Http\Controllers\PageController();
$controller->question($id, $professionSlug);
