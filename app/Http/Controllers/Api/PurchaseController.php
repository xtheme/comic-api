<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\UserPurchaseLog;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PurchaseController extends Controller
{
    const TOPIC = [
        'book_chapter' => '漫画',
        'video' => '視頻',
    ];

    const MODEL = [
        'book_chapter' => 'App\Models\BookChapter',
        'video' => 'App\Models\Video',
    ];

    // 購買項目
    public function purchase(Request $request)
    {
        $user = $request->user();
        $type = $request->input('type');
        $item_id = $request->input('item_id');
        $topic = self::TOPIC[$type];
        $model = self::MODEL[$type];

        $hasBought = UserPurchaseLog::where('user_id', $user->id)->where('type', $type)->where('item_id', $item_id)->first();

        if ($hasBought) {
            return Response::jsonError('您已拥有此商品');
        }

        $item = app($model)->find($item_id);



        return Response::jsonSuccess(__('api.success'), $item);
    }


}
