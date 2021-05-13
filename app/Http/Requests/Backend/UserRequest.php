<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

final class UserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'username' => 'required|max:11',
            'mobile'   => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'username' => '用户昵称',
            'mobile'   => '手机号',
        ];
    }
}
