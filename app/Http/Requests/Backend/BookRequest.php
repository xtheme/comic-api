<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class BookRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'tag' => 'required|array',
            'book_name' => 'required',
            'book_desc' => 'required',
            'pen_name' => 'required',
            'book_thumb' => 'required|image',
            'book_thumb2' => 'required|image',
        ];
    }

    public function attributes()
    {
        return [
            'tag' => '漫画分类',
            'book_name' => '漫画名称',
            'book_desc' => '内容简介',
            'pen_name' => '作者',
            'book_thumb' => '竖向封面',
            'book_thumb2' => '横向封面',
        ];
    }
}
