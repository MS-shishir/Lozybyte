<?php

$allowed = [];

// Allow local development domains in local or testing environments
if (in_array(env('APP_ENV'), ['local', 'testing'])) {
    $allowed = [
        'http://localhost:3000',
        'http://localhost:3001',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:3001',
    ];
}

// Merge allowed origins from env files
$envOrigins = env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL'));
if ($envOrigins) {
    $urls = explode(',', $envOrigins);
    foreach ($urls as $url) {
        $allowed[] = trim($url);
    }
} else {
    // Canonical production fallbacks if env is empty
    $allowed[] = 'https://www.lozybyte.com';
    $allowed[] = 'https://lozybyte.com';
}

$allowed = array_values(array_unique(array_filter($allowed)));

return [
    'paths' => ['api/*', 'sanctum/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => $allowed,
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
