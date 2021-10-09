<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class ConfigRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'options' => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => '配置描述',
            'code' => '配置代号',
            'options' => '配置項',
        ];
    }
}
