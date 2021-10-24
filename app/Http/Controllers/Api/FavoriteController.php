<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FavoriteDestroyRequest;
use App\Http\Requests\Api\FavoriteListRequest;
use App\Http\Requests\Api\FavoriteSaveRequest;
use App\Models\UserFavoriteLog;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class FavoriteController extends BaseController
{
    public function list(FavoriteListRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];

        $logs = UserFavoriteLog::with([$type])->where('user_id', $request->user()->id)->where('type', $type)->orderByDesc('updated_at')->get();

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

    public function save(FavoriteSaveRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $item_id = $input['id'];

        $count = UserFavoriteLog::where('user_id', $request->user()->id)->where('type', $type)->count();

        if ($count >= 50) {
            return Response::jsonError('很抱歉，您的收藏数已达上限！');
        }

        $history = UserFavoriteLog::firstOrCreate([
            'user_id' => $request->user()->id,
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

        UserFavoriteLog::whereIn('id', $ids)->where('user_id', $request->user()->id)->where('type', $type)->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

}
