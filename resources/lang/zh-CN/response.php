<?php

return [
    'create' => [
        'success' => '添加成功',
        'fail'    => '添加失败',
    ],
    'update' => [
        'success' => '修改成功',
        'fail'    => '修改失败',
    ],
    'destroy' => [
        'success' => '删除成功',
        'fail'    => '删除失败',
    ],
    'upload' => [
        'success' => '文件上传成功',
        'removes' => '文件刪除成功',
        'fail'    => [
            'too_big'     => '文件不能大于 :size kb！',
            'mime_type'   => '文件类型不支持！',
            'script_find' => '你所上传的图片可能藏有恶意代码，请通报资安人员处理！',
            'sync'        => '同步文件服务器失败！',
        ],
    ],
    'error'  => [
        'unknown' => '未知的操作'
    ],
    'success'  => [
        'complete' => ':action操作完成！'
    ],
];
