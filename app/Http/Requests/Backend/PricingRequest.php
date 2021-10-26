<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class PricingRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'name' => 'required|between:2,20',
            'label' => 'between:2,10',
            'price' => 'required|numeric',
            'list_price' => 'numeric',
            'coin' => 'numeric',
            'gift_coin' => 'numeric',
            'days' => 'numeric',
            'gift_days' => 'numeric',
            'target' => 'required',
            'status' => 'required',
            'sort' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '方案类型',
            'name' => '方案名稱',
            'label' => '标签',
            'price' => '充值金额',
            'list_price' => '原价',
            'coin' => '金币',
            'gift_coin' => '加赠金币',
            'days' => 'VIP天数',
            'gift_days' => '加赠IP天数',
            'target' => '目标客群',
            'status' => '状态',
            'sort' => '排序',
        ];
    }
}
