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
                'item' => [
                    'id' => $log->item_id,
                    'title' => $log->{$type}->title,
                    'author' => $log->{$type}->author,
                    'cover' => $log->{$type}->cover,
                    'description' => $log->{$type}->description,
                    'keywords' => $log->{$type}->keywords,
                    'view_counts' => shortenNumber($log->{$type}->view_counts),
                    'has_favorite' => true,
                    'created_at' => optional($log->{$type}->created_at)->format('Y-m-d'),
                ],
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function save(FavoriteSaveRequest $request)
    {
        $input = $request->validated();

        $type = $input['type'];
        $item_id = $input['id'];

        switch ($type) {
            case 'video':
                $title = '视频';
                break;
            default:
                $title = '漫画';
                break;
        }

        $count = $request->user()->favorite_logs()->where('type', $type)->count();

        if ($count >= 50) {
            return Response::jsonError('很抱歉，收藏的' . $title . '已达上限！');
        }

        try {
            $target = app('\\App\\Models\\' . Str::studly($type))->findOrFail($item_id);
        } catch (\Exception $e) {
            return Response::jsonError('很抱歉，收藏' . $title . '不存在或已下架！');
        }

        $history = $request->user()->favorite_logs()->firstOrCreate([
            'type' => $type,
            'item_model' => get_class($target),
            'item_id' => $item_id,
        ]);

        if (!$history->wasRecentlyCreated) {
            return Response::jsonError('您已经收藏过此' . $title . '！');
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
