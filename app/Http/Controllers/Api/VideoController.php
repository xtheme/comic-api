<?php

namespace App\Http\Controllers\Api;

use Record;
use App\Models\Video;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Repositories\HistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoController extends BaseController
{
    protected $repository;

    public function __construct(VideoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request, $page = 1)
    {
        $request->merge([
            'page' => $request->has('page') ? $request->input('page') : $page,
            'status' => 1, // 強制查詢上架的視頻
        ]);

        $data = $this->repository->filter($request)->get();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function detail($id)
    {
        // $data = $this->repository->find($id)->toArray();
        $data = Video::withCount(['visit_histories'])->find($id)->toArray();

        // todo 訪問數+1
        Record::from('video')->visit($id);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 紀錄點擊播放
    public function play(Request $request, $id, $series_id)
    {
        // todo 訪問數+1
        Record::from('video')->play($id, $series_id);

        return Response::jsonSuccess(__('api.success'));
    }

    // 猜你喜歡
    public function recommend($id = null)
    {
        $limit = 4;
        $tags = [];

        if ($id) {
            $video = Video::findOrFail($id);
            $tags = $video->tagged_tags;
        }

        if ($tags) {
            $videos = Video::select(['id', 'title', 'cover'])->withAnyTag($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $videos = Video::select(['id', 'title', 'cover'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $videos->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                // 'author' => $book->author,
                // 'description' => $book->description,
                'cover' => $book->cover,
                'tagged_tags' => $book->tagged_tags,

            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
