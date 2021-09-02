<?php

namespace App\Enums;

final class MovieOptions
{
    // 国家
    const COUNTRIES = [
        0 => '日本',
        1 => '韩国',
        2 => '印度',
        5 => '中国大陆',
        6 => '中国台湾',
        7 => '俄罗斯',
        8 => '欧美',
        9 => '其他地区',
    ];

    // 拍摄类型
    const SHOOTING = [
        0 => '专业拍摄',
        1 => '偷拍',
        2 => '自拍',
        3 => '业务拍摄',
    ];

    // 字幕
    const SUBTITLE = [
        0 => '无字幕',
        1 => '中文字幕',
        2 => '英文字幕',
        3 => '中英文字幕',
        4 => '其他字幕',
    ];

    // 馬賽克
    const MOSAIC = [
        0 => '有码',
        1 => '无码',
    ];
}
