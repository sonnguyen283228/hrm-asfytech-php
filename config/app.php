<?php

return [
    'app_name' => 'HRM PHP',
    'base_url' => 'http://localhost:8080',
    'timezone' => 'Asia/Bangkok',

    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'hrm_php',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],

    // Google OAuth2 (điền thông tin từ Google Cloud Console)
    'google' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect_uri' => 'http://localhost:8080/auth/google/callback',
    ],

    // Alert runtime errors to Telegram
    'alerts' => [
        'enabled' => false,
        'telegram_bot_token' => '',
        'telegram_chat_id' => '',
    ],
];
