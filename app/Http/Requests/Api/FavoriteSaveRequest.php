<?php

namespace App\Http\Requests\Api;

class FavoriteSaveRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'id' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '收藏类型',
            'id' => '收藏项目',
        ];
    }
}
