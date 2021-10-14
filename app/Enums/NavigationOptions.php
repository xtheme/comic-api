<?php

namespace App\Enums;

class NavigationOptions
{
    const STATUS_OPTIONS = [
        1 => '上架',
        0 => '下架',
    ];

    const TYPE_OPTIONS = [
        '自定义链接' => '',
        '排行榜单' => 'ranking',
        '分类' => 'category',
        '充值中心' => 'charge',
        '最新漫画' => 'comic/latest',
        '最新动画' => 'video/latest',
        '漫画主题' => 'comic/topic',
        '动画主题' => 'video/topic',
    ];

    const TARGET_OPTIONS = [
        1 => '內部路由',
        2 => '另開浏览器',
    ];
}
