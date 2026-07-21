<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://main.mamokachaplc.com',
        'https://admin.mamokachaplc.com',
        'https://api.mamokachaplc.com',
        'http://main.mamokachaplc.com',
        'http://admin.mamokachaplc.com',
        'http://api.mamokachaplc.com',
        'http://localhost:8080',
        'http://127.0.0.1:8080',
        'http://localhost:3001',
        'http://127.0.0.1:3001',
    ],

    'allowed_origins_patterns' => [
        '#^https://([a-z0-9-]+\.)*mamokachaplc\.com$#i',
        '#^http://([a-z0-9-]+\.)*mamokachaplc\.com$#i',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
