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
        -1 => '否',
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
        0 => '待审核',
        1 => '上架',
        2 => '下架',
    ];

    const RIBBON_OPTIONS = [
        0 => '无',
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

    const REVIEW_OPTIONS = [
        '1' => '待审核',
        '2' => '审核成功',
        '3' => '审核未通过',
        '4' => '屏蔽',
        '5' => '未审核',
    ];

    const CHARGE_OPTIONS = [
        '-1' => '免费',
        '1' => '付费',
    ];
}
