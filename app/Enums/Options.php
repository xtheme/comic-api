<?php

namespace App\Enums;

final class Options
{
    const MOSAIC_OPTIONS = [
        0 => '有码',
        1 => '无码',
    ];

    const SWITCH_OPTIONS = [
        1 => '是',
        0 => '否',
    ];

    const STYLE_OPTIONS = [
        0 => '专业拍摄',
        1 => '偷拍',
        2 => '自拍',
        3 => '业务拍摄',
    ];

    const SUBTITLE_OPTIONS = [
        0 => '无',
        1 => '中文',
        2 => '英文',
        3 => '中英文',
        4 => '其他',
    ];

    const STATUS_OPTIONS = [
        0 => '禁用',
        1 => '启用',
    ];

    const RIBBON_OPTIONS = [
        0 => '无',
        1 => '限时免费',
        2 => '会员抢先',
    ];

    const CAUSER_OPTIONS = [
        'video' => '动画',
        'book' => '漫画',
        'book_safe' => '安全漫画',
    ];
}
