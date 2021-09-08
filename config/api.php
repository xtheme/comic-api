<?php

return [
    'version' => env('API_VERSION', 'v1'),

    'account' => [
        'prefix' => env('ACCOUNT_PREFIX', '茄子漫画'),
    ],

    /**
     * 第三方接口
     */
    'third' => [
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
        'secret' => env('JWT_SECRET', ''),
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

    /**
     * DUN
     */
    'dun' => [
        'id' => env('DUN_SECRET_ID'),
        'key' => env('DUN_SECRET_KEY'),
        'url' => env('DUN_TEXT_API'),
        'bid' => env('DUN_TEXT_BID'),
        'version' => env('DUN_TEXT_VERSION'),
    ],
];
