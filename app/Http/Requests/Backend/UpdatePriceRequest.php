<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class UpdatePriceRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'ids' => 'required',
            'charge_chapter' => 'required|numeric',
            'charge_price' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'ids' => '勾选漫画',
            'charge_chapter' => '开始章节',
            'charge_price' => '金币',
        ];
    }
}
