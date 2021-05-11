<?php

namespace App\Http\Controllers\Api;

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

    public function detail(Request $request, $id = null)
    {
        $data = $this->repository->find($id)->toarray();

        // todo 訪問數+1
        $log = [
            'major_id' => $id,
            'minor_id' => 0,
            'user_vip' => $request->user->subscribed_status,
            'user_id'  => $request->user->id,
            'type'     => 'visit',
            'class'    => 'video',
        ];
        app(HistoryRepository::class)->log($log);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function recommend($limit = 4)
    {
        $data = $this->repository->random($limit);

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
