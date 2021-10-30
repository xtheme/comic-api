<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VisitDestroyRequest;
use App\Http\Requests\Api\VisitListRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\VideoResource;
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

        $data = $logs->transform(function ($item) use ($type, $favorite_logs) {
            $has_favorite = in_array($item->item_id, $favorite_logs) ? true : false;

            if ($type == 'book') {
                $item = (new BookResource($item->{$type}))->favorite($has_favorite);
            } else {
                $item = (new VideoResource($item->{$type}))->favorite($has_favorite);
            }

            return [
                'record_id' => $item->id,
                'recorded_at' => $item->created_at->format('Y-m-d H:i:s'),
                'item' => $item,
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
