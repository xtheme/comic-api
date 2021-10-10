<?php

namespace App\Enums;

final class PricingOptions
{
    const TYPE_OPTIONS = [
        'charge' => '充值金币',
        'vip' => 'VIP方案',
    ];

    const TARGET_OPTIONS = [
        0 => '全用户',
        1 => '首存用戶',
        2 => '续约用户',
    ];

    const STATUS_OPTIONS = [
        1 => '啟用',
        0 => '禁用',
    ];
}
