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

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    list($selector, $validator) = explode(':', $_COOKIE['remember_me'], 2);

    if ($selector && $validator) {
        $sql = "SELECT * FROM auth_tokens WHERE selector = :selector AND expires_at >= NOW() LIMIT 1";
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([':selector' => $selector]);
        $token = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($token) {
            $hashedValidator = hash('sha256', $validator);

            if (hash_equals($token['hashed_validator'], $hashedValidator)) {

                $user = App\Models\User::find($token['user_id']);
                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->first_name;

                }
            }
        }
    }
}