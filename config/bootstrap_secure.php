<?php
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

header("Content-Security-Policy: " .
    "default-src 'self'; " .
    "script-src 'self' https://cdn.jsdelivr.net https://static.cloudflareinsights.com; " .
    "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; " .
    "font-src 'self' https://cdnjs.cloudflare.com; " .
    "img-src 'self' https://placehold.co data:; " .
    "connect-src 'self';"
);

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: same-origin');
require_once __DIR__ . '/bootstrap.php';