<?php

namespace App\Http\Requests\Api;

class FavoriteListRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '收藏类型',
        ];
    }
}
