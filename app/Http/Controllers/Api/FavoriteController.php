<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FavoriteDestroyRequest;
use App\Http\Requests\Api\FavoriteListRequest;
use App\Http\Requests\Api\FavoriteSaveRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class FavoriteController extends BaseController
{
    public function list(FavoriteListRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];

        $logs = $request->user()->favorite_logs()->with([$type, $type . '.tags'])->where('type', $type)->orderByDesc('updated_at')->get();

        $data = $logs->transform(function ($item) use ($type) {
            if ($type == 'book') {
                $item = new BookResource($item->{$type});
            } else {
                $item = new VideoResource($item->{$type});
            }

            return [
                'record_id' => $item->id,
                'recorded_at' => $item->created_at->format('Y-m-d H:i:s'),
                'item' => $item,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function save(FavoriteSaveRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $item_id = $input['id'];

        $count = $request->user()->favorite_logs()->where('type', $type)->count();

        if ($count >= 50) {
            return Response::jsonError('很抱歉，您的收藏数已达上限！');
        }

        $history = $request->user()->favorite_logs()->firstOrCreate([
            // 'user_id' => $request->user()->id,
            'type' => $type,
            'item_model' => '\\App\\Models\\' . Str::studly($type),
            'item_id' => $item_id,
        ]);

        if (!$history->wasRecentlyCreated) {
            return Response::jsonError('您已经收藏过此漫画！');
        }

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function destroy(FavoriteDestroyRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $ids = explode(',', $input['ids']);

        $request->user()->favorite_logs()->where('type', $type)->whereIn('id', $ids)->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

}
