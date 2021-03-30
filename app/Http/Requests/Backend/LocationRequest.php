<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

final class LocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:20',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
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
            'name.required' => '請填寫地點名稱',
            'name.max' => '地點請勿超過20字',
            'phone.required' => '請填寫連絡電話',
            'email.required' => '請填寫連絡信箱',
            'email.email' => '請填寫正確的信箱格式',
            'address.required' => '請填寫完整地址',
        ];
    }
}
