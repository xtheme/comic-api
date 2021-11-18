<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VisitDestroyRequest;
use App\Http\Requests\Api\VisitListRequest;
use Illuminate\Support\Facades\Response;

class VisitController extends BaseController
{
    // 閱覽 (訪問) 歷史紀錄
    public function list(VisitListRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];

        $query = $request->user()->visit_logs()->with([$type, $type . '.tags', $type . '.favorite_logs'])->where('type', $type);

        $logs = (clone $query)->orderByDesc('updated_at')->limit(50)->get();

        // 當前用戶是否收藏
        $book_ids = $logs->pluck('item_id')->toArray();
        $favorite_logs = $request->user()->favorite_logs()->where('type', $type)->whereIn('item_id', $book_ids)->pluck('item_id')->toArray();

        $data = $logs->transform(function ($log) use ($type, $favorite_logs) {
            $has_favorite = in_array($log->item_id, $favorite_logs) ? true : false;

            return [
                'record_id' => $log->id,
                'recorded_at' => $log->created_at->format('Y-m-d H:i:s'),
                // 'type' => $log->type,
                'id' => $log->item_id,
                'title' => $log->{$type}->title,
                'author' => $log->{$type}->author,
                'cover' => ($type == 'book') ? $log->{$type}->vertical_cover : $log->{$type}->cover,
                'description' => $log->{$type}->description,
                'tagged_tags' => $log->{$type}->tagged_tags,
                'view_counts' => shortenNumber($log->{$type}->view_counts),
                'has_favorite' => $has_favorite,
                'created_at' => optional($log->{$type}->created_at)->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function destroy(VisitDestroyRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $ids = explode(',', $input['ids']);

        $request->user()->visit_logs()->where('type', $type)->whereIn('id', $ids)->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

}
