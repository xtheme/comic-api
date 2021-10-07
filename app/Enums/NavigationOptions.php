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
        '排行榜单' => 'eggplant://com.caricature.eggplant/ranking',
        '分类' => 'eggplant://com.caricature.eggplant/category',
        '充值中心' => 'eggplant://com.caricature.eggplant/charge',
        '最新漫画' => 'eggplant://com.caricature.eggplant/comic/latest',
        '最新动画' => 'eggplant://com.caricature.eggplant/video/latest',
        '漫画主题' => 'eggplant://com.caricature.eggplant/comic/topic',
        '动画主题' => 'eggplant://com.caricature.eggplant/video/topic',
    ];

    const TARGET_OPTIONS = [
        0 => '跳外部浏览器',
        1 => '内置浏览器',
    ];
}
