<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PricingpackageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'type.required' => '請填寫套餐名称',
            'type.between' => '套餐名称长度介于2~6字',
            'name.required' => '請填寫小标题',
            'name.between' => '小标题长度介于2~6字',
            'price.required' => '請填寫会员支付价',
            'price.numeric' => '会员支付价请输入数字',
            'list_price.required' => '請填寫会员原价',
            'list_price.numeric' => '会员原价请输入数字',
            'days.required' => '請填寫天数',
            'days.numeric' => '天数请输入数字',
            'label.required' => '請填寫标签',
            'label.between' => '标签长度介于2~6字',
            'status.required' => '請選擇用户状态',
            'sort.required' => '請填寫显示顺序',
            'sort.numeric' => '顺序请输入数字',
        ];
    }
}
