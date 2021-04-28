<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class VideoRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title'       => 'required',
            'cover'       => 'required',
            'status'      => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title'       => '名称',
            'cover'       => '封面图',
            'status'      => '状态',
        ];
    }
}
