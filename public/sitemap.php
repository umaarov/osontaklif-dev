<?php
require_once __DIR__ . '/../config/bootstrap.php';
$db = Database::getInstance()->getConnection();
$baseUrl = APP_URL;
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

function createUrlEntry($loc, $lastmod, $priority = '0.80')
{
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . htmlspecialchars($loc) . '</loc>' . PHP_EOL;
    echo '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>' . PHP_EOL;
    echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

$today = date('Y-m-d H:i:s');
createUrlEntry($baseUrl . '/', $today, '1.00'); // Homepage
createUrlEntry($baseUrl . '/mock.php', $today, '0.90');
createUrlEntry($baseUrl . '/requirements.php', $today, '0.90');
createUrlEntry($baseUrl . '/about.php', $today, '0.70');
createUrlEntry($baseUrl . '/terms.php', $today, '0.50');
createUrlEntry($baseUrl . '/sponsorship.php', $today, '0.50');
createUrlEntry($baseUrl . '/ads.php', $today, '0.50');

$professionsStmt = $db->query("SELECT slug, updated_at FROM professions WHERE is_active = 1");
while ($row = $professionsStmt->fetch(PDO::FETCH_ASSOC)) {
    $url = $baseUrl . '/profession.php?name=' . $row['slug'];
    createUrlEntry($url, $row['updated_at'], '0.90');
}

$professionsStmt->execute();
while ($row = $professionsStmt->fetch(PDO::FETCH_ASSOC)) {
    $url = $baseUrl . '/requirements.php?name=' . $row['slug'];
    createUrlEntry($url, $row['updated_at'], '0.90');
}

$questionsStmt = $db->query(
    "SELECT q.id, q.updated_at, p.slug as profession_slug
     FROM questions q
     JOIN professions p ON q.profession_id = p.id
     WHERE p.is_active = 1"
);
while ($row = $questionsStmt->fetch(PDO::FETCH_ASSOC)) {
    $url = $baseUrl . '/question.php?id=' . $row['id'] . '&pid=' . $row['profession_slug'];
    createUrlEntry($url, $row['updated_at'], '0.80');
}

echo '</urlset>';
