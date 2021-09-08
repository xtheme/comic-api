<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'password' => [
                'required',
                Password::min(8)->mixedCase()->letters()->numbers(),
            ],
            'password_confirmation' => 'required_with:password|same:password',

        ];
    }

    public function attributes()
    {
        return [
            'name' => '账号',
            'password' => '密码',
            'password_confirmation' => '确认密码',
        ];
    }
}
