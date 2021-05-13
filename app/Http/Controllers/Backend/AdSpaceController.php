<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdSpace;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdSpaceController extends Controller
{
    private $repository;

    public function __construct(AdSpaceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $list = $this->repository->filter($request)->paginate();

        return view('backend.ad_space.index', [
            'list' => $list
        ]);
    }

    public function edit($id)
    {
        $data = AdSpace::findOrFail($id);

        return view('backend.ad_space.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();

        $data = AdSpace::findOrFail($id);

        $data->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }
}
