<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class VideoDomainRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'domain' => 'required',
            'encrypt_domain' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '域名名称',
            'domain' => 'CDN 域名',
            'encrypt_domain' => 'CDN 加密域名',
        ];
    }
}
