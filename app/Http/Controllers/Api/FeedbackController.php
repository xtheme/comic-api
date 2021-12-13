<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FeedbackController extends Controller
{
    /**
     * 查询廣告位底下的廣告列表
     */
    public function space()
    {
        $data = [
            0 => [
                'question' => '您覺得什麼部分讓你失望了',
                'options' => [
                    '漫畫都看過了',
                    '漫畫數量太少',
                    '漫畫收費太貴',
                    '網速實在太慢了',
                    '介面設計不夠友善',
                ]
            ],
            1 => [
                'question' => '最期待加入什麼新單元',
                'options' => [
                    '短視頻',
                    '同城約會',
                    '小說',
                    '長視頻',
                ]
            ]
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
