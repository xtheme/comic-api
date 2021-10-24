<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VisitDestroyRequest;
use App\Http\Requests\Api\VisitListRequest;
use App\Models\UserVisitLog;
use Illuminate\Support\Facades\Response;

class VisitController extends BaseController
{
    // 閱覽 (訪問) 歷史紀錄
    public function list(VisitListRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];

        $query = UserVisitLog::with([$type])->where('user_id', $request->user()->id)->where('type', $type);

        $logs = (clone $query)->orderByDesc('updated_at')->limit(50)->get();

        $data = $logs->transform(function ($item) use ($type) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'item_id' => $item->item_id,
                'title' => $item->{$type}->title,
                'author' => $item->{$type}->author,
                'cover' => ($type == 'book') ? $item->{$type}->horizontal_cover : $item->{$type}->cover,
                'tagged_tags' => $item->{$type}->tagged_tags,
                'view_counts' => shortenNumber($item->{$type}->view_counts),
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function destroy(VisitDestroyRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $ids = explode(',', $input['ids']);

        UserVisitLog::whereIn('id', $ids)->where('user_id', $request->user()->id)->where('type', $type)->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

}
