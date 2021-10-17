<?php

namespace App\Enums;

final class UserOptions
{
    const ACTIVE_OPTIONS = [
        0 => '封禁',
        1 => '正常',
    ];

    const BAN_OPTIONS = [
        0 => '正常',
        1 => '黑名单',
    ];
}
