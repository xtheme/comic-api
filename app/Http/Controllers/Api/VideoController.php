<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\VideoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoController extends BaseController
{
    protected $repository;

    public function __construct(VideoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request)
    {
        $request->merge([
            'status' => 1, // 強制查詢上架的視頻
        ]);

        $data = $this->repository->filter($request)->get();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function detail(Request $request, $id = null)
    {
        if ($id) {
            $request->merge([
                'id' => $id
            ]);
        }

        $video = $this->repository->find($request->input('id'));

        $data = $video->toarray();
        $data['series'] = $video->series;

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function recommend($limit = 4)
    {
        $data = $this->repository->random($limit);

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
