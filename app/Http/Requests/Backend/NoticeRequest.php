<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class NoticeRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'notice_title' => 'required',
            'notice_content' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'notice_title' => '公告标题',
            'notice_content' => '公告详情',
        ];
    }
}
