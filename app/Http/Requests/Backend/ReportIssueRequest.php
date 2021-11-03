<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class ReportIssueRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'   => 'required',
            'sort'   => 'required|numeric',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'   => '问题名称',
            'sort'   => '排序',
            'status' => '状态',
        ];
    }
}
