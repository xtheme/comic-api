<?php

namespace App\Http\Requests\Api;

class PayRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'plan_id' => 'required|numeric',
            'gateway_id' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'plan_id' => '支付方案',
            'gateway_id' => '支付渠道',
        ];
    }
}
