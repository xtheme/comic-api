<?php

return [
    'success' => '请求成功！',
    'unauthenticated' => '未认证的身份！', // 请先登录会员
    'register' => [
        'name' => [
            // 'exists' => '您所使用的帐号或信箱已被注册！',
            'exists' => '你输入的帐号已被使用！',
        ],
    ],
    'login' => [
        'password' => [
            'wrong' => '密码错误！',
        ],
        'status' => [
            'ban' => '帐号已封禁, 申诉请联系客服人员！',
        ],
    ],
    'logout' => [
        'success' => '登出成功！',
    ],
];
