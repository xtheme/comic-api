<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\Password;

class UserModifyRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'min:2|max:16|alpha_dash',
            'password' => ['confirmed', Password::min(4)],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '帐号',
            'password' => '密码',
        ];
    }
}
