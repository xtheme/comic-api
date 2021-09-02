<?php

namespace App\Http\Controllers\Api;

use App\Models\LadyCity;
use App\Models\Movie;
use App\Repositories\Contracts\LadyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LadyController extends BaseController
{
    private $repository;

    public function __construct(LadyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function cities(Request $request)
    {
        $collect = LadyCity::with(['children', 'children.children'])->where('p_id', 0)->get();

        return Response::jsonSuccess(__('api.success'), $collect);
    }

    public function list(Request $request, $city = null)
    {
        $request->merge([
            'state'   => 1,
            'city_id' => $city,
            'order'   => 'created_at',
            'sort'    => 'desc',
        ]);

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
