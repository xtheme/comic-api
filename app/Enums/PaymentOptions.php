<?php

namespace App\Enums;

final class PaymentOptions
{
    const STATUS_OPTIONS = [
        1 => '啟用',
        0 => '停用',
    ];

    const TARGET_OPTIONS = [
        'new_tab' => '另开页面',
        'iframe' => '内嵌页面',
    ];

    const ICON_OPTIONS = [
        'alipay' => '支付宝',
        'weixin' => '微信',
    ];
}
