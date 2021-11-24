<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'captcha' => 'required|captcha_api:'. request()->input('key') . ',math',
            'name' => 'required|min:2|max:16|alpha_dash',
            'password' => [
                'required',
                // Password::min(8)->mixedCase()->letters()->numbers(),
                Password::min(6),
            ],
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function attributes()
    {
        return [
            'captcha' => '验证码',
            'name' => '帐号',
            'password' => '密码',
            'password_confirmation' => '确认密码',
        ];
    }
}
