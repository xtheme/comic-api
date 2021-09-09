<?php

namespace App\Http\Controllers\Api;

use App\Models\ResumeCity;
use App\Models\Movie;
use App\Repositories\Contracts\ResumeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResumeController extends BaseController
{
    private $repository;

    public function __construct(ResumeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function cities(Request $request)
    {
        $collect = ResumeCity::with(['children', 'children.children'])->where('p_id', 0)->get();

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
