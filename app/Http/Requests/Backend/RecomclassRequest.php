<?php

namespace App\Http\Requests\Backend;


use App\Http\Requests\BaseRequest;

class RecomclassRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'listorder' => 'required|numeric',
            'title' => 'required',
            'icon' => 'required',
            'style' => 'required',
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
            'listorder.required' => '請填寫排序',
            'listorder.numeric' => '排序必须为数字',
            'title.required' => '請填寫推荐名称',
            'icon.required' => '請上传图标',
            'style.required' => '請选择展示风格'
        ];
    }
}
