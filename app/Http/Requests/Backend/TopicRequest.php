<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class TopicRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title'     => 'required',
            'spotlight' => 'numeric',
            'row'       => 'numeric',
            'sort'      => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'title'     => '模块标题',
            'spotlight' => '首笔聚焦',
            'row'       => '每行笔数',
            'sort'      => '模块排序',
        ];
    }
}
