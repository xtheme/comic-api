<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FavoriteDestroyRequest;
use App\Http\Requests\Api\FavoriteListRequest;
use App\Http\Requests\Api\FavoriteSaveRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class FavoriteController extends BaseController
{
    public function list(FavoriteListRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];

        $logs = $request->user()->favorite_logs()->with([$type, $type . '.tags'])->where('type', $type)->orderByDesc('updated_at')->get();

        $data = $logs->transform(function ($log) use ($type) {
            return [
                'record_id' => $log->id,
                'recorded_at' => $log->created_at->format('Y-m-d H:i:s'),
                // 'type' => $log->type,
                'id' => $log->item_id,
                'title' => $log->{$type}->title,
                'author' => $log->{$type}->author,
                'cover' => ($type == 'book') ? $log->{$type}->horizontal_cover : $log->{$type}->cover,
                'description' => $log->{$type}->description,
                'tagged_tags' => $log->{$type}->tagged_tags,
                'view_counts' => shortenNumber($log->{$type}->view_counts),
                'has_favorite' => true,
                'created_at' => optional($log->{$type}->created_at)->format('Y-m-d'),
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

        try {
            $target = app('\\App\\Models\\' . Str::studly($type))->findOrFail($item_id);
        } catch (\Exception $e) {
            return Response::jsonError('很抱歉，收藏项目不存在或已下架！');
        }

        $history = $request->user()->favorite_logs()->firstOrCreate([
            'type' => $type,
            'item_model' => get_class($target),
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
