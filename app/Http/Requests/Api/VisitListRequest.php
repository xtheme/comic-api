<?php

namespace App\Http\Requests\Api;

class VisitListRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '访问类型',
        ];
    }
}
