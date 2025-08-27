<?php
require_once __DIR__ . '/bootstrap.php';

$isProduction = (APP_ENV === 'production');

$csp = [
    'default-src' => ["'self'"],
    'style-src' => ["'self'", 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com'],
    'font-src' => ["'self'", 'https://cdnjs.cloudflare.com'],
    'img-src' => ["'self'", 'https://placehold.co', 'data:'],
    'connect-src' => ["'self'"],
    'script-src' => ["'self'", 'https://cdn.jsdelivr.net', 'https://static.cloudflareinsights.com']
];

if ($isProduction) {
    $csp['script-src'][] = APP_URL;
    $csp['connect-src'][] = APP_URL;
    $csp['script-src'][] = "'sha256-XCXkABNL2InS/sqKk/7oRDmOkYFjtnJroKulj0irdjI='";
    $csp['style-src'][] = "'unsafe-inline'";
} else {
    $csp['script-src'][] = APP_URL;
    $csp['connect-src'][] = APP_URL;
    $csp['style-src'][] = "'unsafe-inline'";
    $csp['script-src'][] = "'unsafe-inline'";
}

$cspHeader = '';
foreach ($csp as $directive => $sources) {
    $cspHeader .= "{$directive} " . implode(' ', array_unique($sources)) . '; ';
}

header("Content-Security-Policy: " . rtrim($cspHeader));

if ($isProduction) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
