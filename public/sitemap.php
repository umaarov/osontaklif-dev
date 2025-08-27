<?php
error_reporting(0);
ini_set('display_errors', 0);

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);

try {
    require_once __DIR__ . '/../config/bootstrap.php';
} catch (Throwable $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}

if (!defined('APP_URL') || !APP_URL) {
    header("HTTP/1.1 500 Internal Server Error");
    error_log('Sitemap generation failed: APP_URL is not defined.');
    exit;
}

$db = Database::getInstance()->getConnection();
$baseUrl = APP_URL;
header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

function createUrlEntry($loc, $lastmod, $priority = '0.80')
{
    $lastmodDate = date('Y-m-d', strtotime($lastmod));
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . htmlspecialchars($loc) . '</loc>' . PHP_EOL;
    echo '    <lastmod>' . $lastmodDate . '</lastmod>' . PHP_EOL;
    echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

$today = date('Y-m-d H:i:s');
createUrlEntry($baseUrl . '/', $today, '1.00');
createUrlEntry($baseUrl . '/mock.php', $today, '0.90');
createUrlEntry($baseUrl . '/requirements.php', $today, '0.90');
createUrlEntry($baseUrl . '/about.php', $today, '0.70');
createUrlEntry($baseUrl . '/terms.php', $today, '0.50');
createUrlEntry($baseUrl . '/sponsorship.php', $today, '0.50');
createUrlEntry($baseUrl . '/ads.php', $today, '0.50');

$professionsStmt = $db->prepare("SELECT slug, updated_at FROM professions WHERE is_active = 1");
$professionsStmt->execute();

while ($row = $professionsStmt->fetch(PDO::FETCH_ASSOC)) {
    $professionUrl = $baseUrl . '/profession.php?name=' . $row['slug'];
    createUrlEntry($professionUrl, $row['updated_at'], '0.90');

    $requirementUrl = $baseUrl . '/requirements.php?name=' . $row['slug'];
    createUrlEntry($requirementUrl, $row['updated_at'], '0.90');
}

$questionsStmt = $db->prepare(
    "SELECT q.id, q.updated_at, p.slug as profession_slug
     FROM questions q
     JOIN professions p ON q.profession_id = p.id
     WHERE p.is_active = 1"
);
$questionsStmt->execute();

while ($row = $questionsStmt->fetch(PDO::FETCH_ASSOC)) {
    $url = $baseUrl . '/question.php?id=' . $row['id'] . '&pid=' . $row['profession_slug'];
    createUrlEntry($url, $row['updated_at'], '0.80');
}

echo '</urlset>';
