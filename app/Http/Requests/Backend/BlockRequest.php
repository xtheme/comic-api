<?php

namespace App\Http\Requests\Backend;


use App\Http\Requests\BaseRequest;

class BlockRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'focus' => 'numeric',
            'row'   => 'numeric',
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
            'title.required' => '模块名称不能为空',
            'focus.numeric' => '聚焦数必須為數字',
            'row.numeric' => '行数必須為數字',
            'sort.required' => '排序不能为空',
            'sort.numeric' => '排序必須為數字',

        ];
    }
}
