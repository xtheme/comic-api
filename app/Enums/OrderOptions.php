<?php

namespace App\Enums;

final class OrderOptions
{
    const TYPE_OPTIONS = [
        'charge' => '充值金币',
        'vip' => 'VIP方案',
    ];

    const PLATFORM_OPTIONS = [
        'h5' => '手機',
        'pc' => 'PC',
        'ios' => '蘋果',
        'android' => '安卓',
    ];

    const STATUS_OPTIONS = [
        0 => '待支付',
        1 => '支付成功',
        2 => '支付失敗',
        3 => '補單',
    ];
}
