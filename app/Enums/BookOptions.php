<?php

namespace App\Enums;

final class BookOptions
{
    const STATUS_OPTIONS = [
        0 => '下架',
        1 => '上架',
    ];

    const REVIEW_OPTIONS = [
        0 => '待审核',
        1 => '审核成功',
        2 => '图片不完整',
        3 => '重复的漫画',
        4 => '版权争议',
    ];

    const TYPE_OPTIONS = [
        1 => '日漫',
        2 => '韩漫',
        3 => '美漫',
        4 => '写真',
        5 => 'CG',
    ];

    const CHARGE_OPTIONS = [
        0 => '免费',
        1 => '付费',
    ];
}
