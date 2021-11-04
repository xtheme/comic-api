<?php

namespace App\Enums;

final class BookOptions
{
    const STATUS_OPTIONS = [
        0 => '下架',
        1 => '上架',
    ];

    const REVIEW_OPTIONS = [
        1 => '待审核',
        2 => '审核成功',
        3 => '审核未通过',
        4 => '屏蔽',
        5 => '未审核',
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
