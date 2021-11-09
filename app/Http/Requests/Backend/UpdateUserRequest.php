<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;

final class UpdateUserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:16|alpha_dash',
            'email'   => 'email',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '帐号',
            'email'   => 'Email',
        ];
    }
}
