<?php

namespace App\Http\Requests\Api;

class FavoriteDestroyRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'ids' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '收藏类型',
            'ids' => '收藏项目',
        ];
    }
}
