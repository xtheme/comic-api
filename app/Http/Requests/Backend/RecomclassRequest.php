<?php

namespace App\Http\Requests\Backend;


use App\Http\Requests\BaseRequest;

class RecomclassRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'listorder' => 'required|numeric',
            'title' => 'required',
            'icon' => 'required',
            'style' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'listorder' => '排序',
            'title' => '推荐名称',
            'icon' => '图标',
            'style' => '展示风格',
        ];
    }
}
