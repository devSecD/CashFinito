// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000'], // Vue dev server
'allowed_headers' => ['*'],

// config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),

// .env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000