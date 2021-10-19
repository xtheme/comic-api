<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class NoticeRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '公告标题',
        ];
    }
}
