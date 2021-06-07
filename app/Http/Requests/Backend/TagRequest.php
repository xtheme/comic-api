<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class TagRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'suggest'   => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '标签',
            'suggest'   => '前台显示',
        ];
    }
}
