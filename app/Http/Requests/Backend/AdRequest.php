<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class AdRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'space_id' => 'required|numeric',
            'sort' => 'required|numeric',
            'banner' => 'required'
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'space_id' => '广告位置',
            'sort' => '排序',
            'banner' => '广告图',
        ];
    }
}
