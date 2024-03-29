<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    /**
     * 1. 檢查是否已登入
     * 2. 檢查是否重複購買
     * 3. 檢查錢包餘額是否足夠
     * 4. 建立購買紀錄
     * 5. 錢包扣款
     */
    public function purchase(Request $request)
    {
        $type = $request->input('type');
        $item_id = $request->input('item_id');

        $item_model = '\\App\\Models\\' . Str::studly($type);

        $product = app($item_model)::findOrFail($item_id);

        try {
            $request->user()->purchase($product);
        } catch (\Exception $e) {
            return Response::jsonError($e->getMessage());
        }

        $response = [
            'wallet' => $request->user()->getWallet(),
        ];

        return Response::jsonSuccess(__('api.success'), $response);
    }
}
