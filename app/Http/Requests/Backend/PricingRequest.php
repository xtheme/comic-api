<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class PricingRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'type' => 'required|between:2,6',
            'name' => 'required|between:2,6',
            'price' => 'required|numeric',
            'list_price' => 'required|numeric',
            'days' => 'required|numeric',
            'label' => 'required|between:2,6',
            'status' => 'required',
            'sort' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '套餐名称',
            'name' => '小标题',
            'price' => '支付价格',
            'list_price' => '原价',
            'days' => '天数',
            'label' => '标签',
            'status' => '状态',
            'sort' => '排序',
        ];
    }
}
