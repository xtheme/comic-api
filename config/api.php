<?php

return [
    'version' => env('API_VERSION', 'v5'),

    /**
     * 第三方接口
     */
    'api' => [
        'upload_url' => env('API_UPLOAD_URL', true),
    ],

    /**
     * 數據加密
     */
    'encrypt' => [
        'response' => env('ENCRYPT_RESPONSE', true),
        'image' => env('ENCRYPT_IMAGE', true),
    ],

    /**
     * AES
     */
    'aes' => [
        'method' => env('AES_METHOD', 'AES-128-ECB'),
        'key' => env('AES_KEY', ''),
        'options' => 0,
        'iv' => env('AES_IV', ''),
    ],

    /**
     * JWT
     */
    'jwt' => [
        'algorithm' => env('JWT_ALGORITHM', 'HS256'),
        'key' => env('JWT_KEY', ''),
        'ttl' => env('JWT_TTL', 7776000),
        'leeway' => env('JWT_LEEWAY', 60),
    ],

    /**
     * SMS
     */
    'sms' => [
        'check' => env('SMS_CHECK', false),
        'url' => env('SMS_URL'),
        'token' => env('SMS_TOKEN'),
        'product' => env('SMS_PRODUCT'),
        'message' => env('SMS_MESSAGE'),
    ],
];
