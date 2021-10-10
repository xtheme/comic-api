<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class PaymentRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'fee_percentage' => 'required|numeric',
            'daily_limit' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '渠道名稱',
            'fee_percentage' => '手續費%',
            'daily_limit' => '每日限額',
        ];
    }
}
