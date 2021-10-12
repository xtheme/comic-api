<?php

namespace App\Http\Controllers\Api;

use App\Models\UserVisitBook;
use App\Models\VideoVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VisitHistoryController extends BaseController
{
    private function getBookVisitHistories(Request $request)
    {
        $histories = UserVisitBook::has('book')->where('user_id', $request->user->id)->orderByDesc('updated_at')->get();

        $histories = $histories->transform(function ($item) {
            return [
                'id' => $item->id,
                'book_id' => $item->book_id,
                'title' => $item->book->title,
                'author' => $item->book->author,
                'cover' => $item->book->cover,
                // 'ribbon' => $item->video->ribbon,
                'tagged_tags' => $item->book->tagged_tags,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return $histories;
    }

    private function getVideoVisitHistories(Request $request)
    {
        $histories = VideoVisit::has('video')->where('user_id', $request->user->id)->orderByDesc('updated_at')->get();

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
                $histories = $this->getBookVisitHistories($request);
                break;
            case 'video':
                $histories = $this->getVideoVisitHistories($request);
                break;
        }

        return Response::jsonSuccess(__('api.success'), $histories);
    }

    public function destroy(Request $request, $type)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($type) {
            case 'book':
                UserVisitBook::whereIn('id', $ids)->where('user_id', $request->user->id)->delete();

                $histories = $this->getBookVisitHistories($request);
                break;
            case 'video':
                VideoVisit::whereIn('id', $ids)->where('user_id', $request->user->id)->delete();

                $histories = $this->getVideoVisitHistories($request);
                break;
        }

        return Response::jsonSuccess(__('response.destroy.success'), $histories);
    }

}
