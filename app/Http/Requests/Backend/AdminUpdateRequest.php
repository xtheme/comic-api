<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;

class AdminUpdateRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'role' => 'required',
            'username' => 'alpha_num',
            'nickname' => 'required|alpha_num',
            'password' => 'nullable|required_with:new_password|password',
            'new_password' => [
                'nullable',
                'required_with:password',
                Password::min(6),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'role' => '角色',
            'username' => '登录帐号',
            'nickname' => '昵称',
            'password' => '原密码',
            'new_password' => '新密码',
        ];
    }
}
