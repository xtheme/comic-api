<?php

namespace App\Http\Controllers\Api;

use App\Models\UserFavoriteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FavoriteHistoryController extends BaseController
{
    private function getBookFavoriteHistories(Request $request)
    {
        $histories = UserFavoriteLog::where('user_id', $request->user()->id)->orderByDesc('updated_at')->get();

        $histories = $histories->transform(function ($item) {
            return [
                'id' => $item->id,
                'book_id' => $item->item_id,
                'title' => $item->relation->title,
                'author' => $item->relation->author,
                'cover' => $item->relation->cover,
                // 'ribbon' => $item->video->ribbon,
                'tagged_tags' => $item->relation->tagged_tags,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return $histories;
    }

    private function getVideoFavoriteHistories(Request $request)
    {
        $histories = UserFavoriteLog::where('user_id', $request->user()->id)->orderByDesc('updated_at')->get();

        $histories = $histories->transform(function ($item) {
            return [
                'id' => $item->id,
                'video_id' => $item->video_id,
                'title' => $item->video->title,
                'author' => $item->video->author,
                'cover' => $item->video->cover,
                'ribbon' => $item->video->ribbon,
                'tagged_tags' => $item->video->tagged_tags,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return $histories;
    }

    // 閱覽 (訪問) 歷史紀錄
    public function list(Request $request, $type)
    {
        switch ($type) {
            case 'book':
                $histories = $this->getBookFavoriteHistories($request);
                break;
            case 'video':
                $histories = $this->getVideoFavoriteHistories($request);
                break;
        }

        return Response::jsonSuccess(__('api.success'), $histories);
    }

    public function save(Request $request, $type)
    {
        switch ($type) {
            case 'book':
                $history = UserFavoriteLog::firstOrCreate([
                    'book_id' => $request->input('book_id'),
                    'chapter_id' => $request->input('chapter_id') ?? 0,
                    'user_id' => $request->user()->id,
                ]);

                if (!$history->wasRecentlyCreated) {
                    return Response::jsonError('您已经收藏过此漫画！');
                }

                $histories = $this->getBookFavoriteHistories($request);
                break;
            case 'video':
                $history = VideoFavorite::firstOrCreate([
                    'video_id' => $request->input('video_id'),
                    'series_id' => $request->input('series_id') ?? 0,
                    'user_id' => $request->user()->id,
                ]);

                if (!$history->wasRecentlyCreated) {
                    return Response::jsonError('您已经收藏过此动画！');
                }

                $histories = $this->getVideoFavoriteHistories($request);
                break;
        }

        return Response::jsonSuccess(__('response.create.success'), $histories);
    }

    public function destroy(Request $request, $type)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($type) {
            case 'book':
                UserFavoriteLog::whereIn('id', $ids)->where('user_id', $request->user()->id)->delete();

                $histories = $this->getBookFavoriteHistories($request);
                break;
            case 'video':
                VideoFavorite::whereIn('id', $ids)->where('user_id', $request->user()->id)->delete();

                $histories = $this->getVideoFavoriteHistories($request);
                break;
        }

        return Response::jsonSuccess(__('response.destroy.success'), $histories);
    }

}
