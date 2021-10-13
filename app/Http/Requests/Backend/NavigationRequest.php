<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class NavigationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'link' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '导航名称',
            'link' => '链接',
        ];
    }
}
