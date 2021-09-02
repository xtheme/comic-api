<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MovieController extends BaseController
{
    private $repository;

    public function __construct(MovieRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request, $type)
    {
        switch ($type) {
            // 最新
            case 'latest':
                $request = $request->merge([
                    'status' => 1,
                    'order' => 'created_at',
                    'sort' => 'desc',
                ]);
                break;
            // 熱門
            case 'popular':
                $request = $request->merge([
                    'status' => 1,
                    'order' => 'views',
                    'sort' => 'desc',
                ]);
                break;
        }

        $collect = $this->repository->filter($request)->get();

        $data = $this->repository->collectFormat($collect);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function detail($id)
    {
        $video = Movie::find($id);

        $data = $this->repository->format($video);

        // 訪問數+1
        $video->increment('views');

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
