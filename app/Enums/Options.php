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
