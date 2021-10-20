<?php

namespace App\Http\Requests\Api;

class PayRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'pricing_id' => 'required|numeric',
            'payment_id' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'pricing_id' => '支付方案',
            'payment_id' => '支付渠道',
        ];
    }
}
