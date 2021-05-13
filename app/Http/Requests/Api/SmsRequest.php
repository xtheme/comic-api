<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class SmsRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'area' => 'required|numeric',
            'mobile'   => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'area' => '区码',
            'mobile' => '手机号',
        ];
    }
}
