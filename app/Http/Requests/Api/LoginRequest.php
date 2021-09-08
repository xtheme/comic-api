<?php

namespace App\Http\Requests\Api;

class LoginRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'password' => 'required',

        ];
    }

    public function attributes()
    {
        return [
            'name' => '账号',
            'password' => '密码',
        ];
    }
}
