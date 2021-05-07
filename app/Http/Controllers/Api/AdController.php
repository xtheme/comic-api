<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdController extends Controller
{

    private $repository;

    public function __construct(AdSpaceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function space(Request $request, $id)
    {
        $data = $this->repository->ads($request, $id)->get()->first();

        return Response::jsonSuccess('返回成功', $data);
    }
}
