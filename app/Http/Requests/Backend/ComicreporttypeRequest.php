<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class ComicreporttypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'sort' => 'required|numeric',
            'status' => 'required',
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
            'name.required' => '請填寫举报问题名称',
            'sort.required' => '請填寫排序',
            'sort.numeric' => '排序必须为数字',
            'status.required' => '請选择使用状态',
        ];
    }
}
