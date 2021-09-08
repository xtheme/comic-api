<?php

return [
    'version' => env('API_VERSION', 'v1'),
    'secret' => env('API_SECRET', ''),

    'account' => [
        'prefix' => env('ACCOUNT_PREFIX', '茄子漫画'),
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
     * Lady
     */
    'lady' => [
        'img_domain' => 'http://lfimg.gs562.cn/',
    ],

    /**
     * Video
     */
    'video' => [
        'hls_domain' => 'https://1107newcdn.3r5ewowo.com',
        'img_domain' => 'https://qqc.mnbvvbnm.com',
    ],
];
