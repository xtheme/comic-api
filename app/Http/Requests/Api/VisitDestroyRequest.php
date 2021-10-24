<?php

namespace App\Http\Requests\Api;

class VisitDestroyRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'ids' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '访问类型',
            'ids' => '访问项目',
        ];
    }
}
