<?php

namespace App\Http\Requests\Api;

class RegisterRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required_with:password|same:password',

        ];
    }

    public function attributes()
    {
        return [
            'username' => '账号',
            'password' => '密码',
            'password_confirmation' => '确认密码',
        ];
    }
}
