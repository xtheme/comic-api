<?php

namespace App\Http\Requests\Api;

class MobileRequest extends BaseApiRequest
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
