<?php

return [
    'version' => env('API_VERSION', 'v1'),
    'secret' => env('API_SECRET', ''),

    /**
     * 數據加密
     */
    'encrypt' => [
        'response' => env('ENCRYPT_RESPONSE', false),
        'image' => env('ENCRYPT_IMAGE', false),
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
     * 漫畫
     */
    'comic' => [
        'image_domain' => 'http://pic.honganll.com',
        'encrypt_image_domain' => 'http://pic.honganll.com',
    ],

    /**
     * Resume
     */
    'resume' => [
        'image_domain' => 'http://lfimg.gs562.cn',
    ],

    /**
     * Video
     */
    'video' => [
        'hls_domain' => 'https://1107newcdn.3r5ewowo.com',
        'image_domain' => 'https://pic.vip88991.com',
        'encrypt_image_domain' => 'https://pic.vip88991.com',
    ],
];
