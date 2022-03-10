<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class ResumeRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'nickname' => 'required',
            'birth_year' => 'required|numeric',
            'cup' => 'regex:/^[2-3]{1}+[02468]{1}+[A-Za-z]{1}$/i',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'content.qq' => 'required_without_all:content.wechat,content.phone',
            'content.wechat' => 'required_without_all:content.qq,content.phone',
            'content.phone' => 'required_without_all:content.qq,content.wechat',
            'point' => 'required|numeric',
            'body_shape' => 'required|array',
            'service' => 'required|array',
            'cover' => 'required|ends_with:.jpg,.png',
            'video' => 'nullable|ends_with:.mp4',
        ];
    }

    public function attributes()
    {
        return [
            'nickname' => '昵称',
            'birth_year' => '出生年份',
            'cup' => '罩杯',
            'province_id' => '省份',
            'city_id' => '城市',
            'area_id' => '区县',
            'content.qq' => 'QQ',
            'content.wechat' => '微信',
            'content.phone' => '手机号',
            'point' => '解锁点数',
            'body_shape' => '身型',
            'service' => '服务项目',
            'cover' => '照片',
            'video' => '视频',
        ];
    }
}
