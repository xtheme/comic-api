<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends BaseController
{
    public function order(Request $request)
    {
        $user = $request->user();
        $page = $request->input('page') ?? 1;
        $size = $request->input('size') ?? 20;

        $query = $user->orders();

        $count = (clone $query)->count();

        $total_page = ceil($count / $size);

        $logs = (clone $query)->latest()->forPage($page, $size)->get();

        $list = $logs->map(function ($log) {
            return [
                'order_no' => $log->order_no,
                'type' => OrderOptions::TYPE_OPTIONS[$log->type],
                'amount' => $log->amount,
                'plan' => $log->plan_options,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'status' => $log->status,
                // 'status' => OrderOptions::STATUS_OPTIONS[$log->status],
                'transaction_at' => optional($log->transaction_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $data = [
            'page' => (int) $page,
            'size' => (int) $size,
            'total_page' => (int) $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function recharge(Request $request)
    {
        $user = $request->user();
        $page = $request->input('page') ?? 1;
        $size = $request->input('size') ?? 20;

        $query = $user->recharge_logs();

        $count = (clone $query)->count();

        $total_page = ceil($count / $size);

        $logs = (clone $query)->latest()->forPage($page, $size)->get();

        $list = $logs->map(function ($log) {
            return [
                'order_no' => $log->order_no,
                'type' => OrderOptions::TYPE_OPTIONS[$log->type],
                'coin' => $log->coin,
                'gift_coin' => $log->gift_coin,
                'days' => $log->days,
                'gift_days' => $log->gift_days,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $data = [
            'page' => (int) $page,
            'size' => (int) $size,
            'total_page' => (int) $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function purchase(Request $request)
    {
        $user = $request->user();
        $type = $request->input('type') ?? 'book_chapter';
        $page = $request->input('page') ?? 1;
        $size = $request->input('size') ?? 20;

        $query = $user->purchase_logs()->with([$type])->where('type', $type);

        $count = (clone $query)->count();

        $total_page = ceil($count / $size);

        $logs = (clone $query)->latest()->forPage($page, $size)->get();

        $list = $logs->map(function ($log) {
            return [
                'event' => $log->item_type,
                'title' => $log->item_title,
                'type' => $log->type,
                'id' => $log->item_id,
                'price' => $log->item_price,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $data = [
            'page' => (int) $page,
            'size' => (int) $size,
            'total_page' => (int) $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
