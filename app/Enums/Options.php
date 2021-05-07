<?php

namespace App\Enums;

final class Options
{
    const STATUS_OPTIONS = [
        1  => '上架',
        -1 => '下架',
    ];

    const RIBBON_OPTIONS = [
        1 => '限时免费',
        2 => '会员抢先',
    ];

    const CAUSER_OPTIONS = [
        'video' => '动画',
        'book' => '漫画',
    ];

    const SORTING_OPTIONS = [
        'created_at' => '上架时间',
        'views' => '热度',
    ];
}
