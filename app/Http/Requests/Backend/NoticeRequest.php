<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class NoticeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'notice_title' => 'required',
            'notice_content' => 'required',
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
            'notice_title.required' => '請填寫公告标题',
            'notice_content.required' => '請填寫公告详情',
        ];
    }
}
