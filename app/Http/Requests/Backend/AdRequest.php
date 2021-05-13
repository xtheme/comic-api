<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class AdRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'space_id' => 'required|numeric',
            'name' => 'required',
            'sort' => 'required|numeric',
            'platform' => 'required|numeric',
            'jump_type' => 'required|numeric',
            'times' => 'numeric',
            'image' => 'required|image'
        ];

        if ($this->method() == 'PUT') {
            unset($rules['image']);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'space_id' => '广告位置',
            'name' => '广告名称',
            'sort' => '排序',
            'platform' => '所属平台',
            'jump_type' => '跳转类型',
            'url' => '广告地址',
            'times' => '显示时间',
            'image' => '广告图',
        ];
    }
}
