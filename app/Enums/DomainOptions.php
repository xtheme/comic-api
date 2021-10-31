<?php

namespace App\Enums;

final class DomainOptions
{
    const TYPE_OPTIONS = [
        'backend' => '后台',
        'frontend' => '推广',
        'image' => '圖片',
        'video' => '视频',
    ];

    const STATUS_OPTIONS = [
        0 => '配置中',
        1 => '启用',
        2 => '备用',
        3 => '被拦截',
        4 => '停用',
    ];
}
