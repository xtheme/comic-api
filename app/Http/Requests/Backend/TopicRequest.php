<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class TopicRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'filter_id' => 'required|numeric',
            'spotlight' => 'numeric',
            'row' => 'numeric',
            'sort' => 'required|numeric',
            'limit' => 'required|numeric',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '类型',
            'filter_id' => '筛选器',
            'spotlight' => '首笔聚焦',
            'row' => '每行笔数',
            'sort' => '模块排序',
            'limit' => '模块展示笔数',
            'status' => '状态',
        ];
    }
}
