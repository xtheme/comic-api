<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class VideoDomainRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'domain' => 'required|url',
            'encrypt_domain' => 'required|url',
            'sort' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '域名名称',
            'domain' => 'CDN 域名',
            'encrypt_domain' => 'CDN 加密域名',
            'sort' => '排序',
        ];
    }
}
