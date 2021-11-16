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
        'key' => base64_decode(env('ENCRYPT_KEY', '')),
        'nonce' => base64_decode(env('ENCRYPT_NONCE', '')),
        'domains' => [
            'http://e93uka.sjzleon.com',
            'http://t79ca8.sjzleon.com',
            'http://r7ncvu.bxcshihu.com',
        ],
    ],

];
