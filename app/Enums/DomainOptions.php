<?php

namespace App\Enums;

final class DomainOptions
{
    const TYPE_OPTIONS = [
        'api' => 'API',
        'wap' => '网站',
        'android' => '安卓',
        // 'image' => '圖片',
        // 'video' => '视频',
    ];

    const STATUS_OPTIONS = [
        0 => '配置中',
        1 => '启用',
        2 => '备用',
        3 => '被拦截',
        4 => '停用',
    ];
}
