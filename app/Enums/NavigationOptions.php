<?php

namespace App\Enums;

class NavigationOptions
{
    const STATUS_OPTIONS = [
        1 => '上架',
        0 => '下架',
    ];

    const TARGET_OPTIONS = [
        1 => '使用筛选器',
        2 => '另開浏览器',
    ];
}
