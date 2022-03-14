<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class BookRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'tag' => 'array',
            'title' => 'required',
            'author' => 'required',
            'cover' => 'required',
            'end' => 'required',
            'type' => 'required',
            'status' => 'required',
            'operating' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'tag' => '漫画分类',
            'title' => '漫画名称',
            'author' => '作者',
            'cover' => '封面',
            'end' => '连载状态',
            'type' => '漫画类型',
            'status' => '上架状态',
            'operating' => '采集方式',
        ];
    }
}
