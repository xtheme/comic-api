<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class VideoSeriesRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'episode'         => 'required',
            'title'           => 'required',
            'charge'          => 'required',
            'status'          => 'required',
            'video_domain_id' => 'required',
            'link'            => 'required|starts_with:/|ends_with:m3u8',
        ];
    }

    public function attributes()
    {
        return [
            'episode'         => '集数',
            'title'           => '影集标题',
            'charge'          => '付费观看',
            'status'          => '状态',
            'video_domain_id' => '视频域名',
            'link'            => '视频链结',
        ];
    }
}
