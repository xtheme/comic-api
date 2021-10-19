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

    const CLASS_TYPE = [
        'video' => '动画',
        'comic' => '漫画',
        'other' => '其他'
    ];

    const DISPLAY_TYPE = [
        1 => '单图',
        2 => '轮播',
        3 => '跑马灯',
    ];

    public function __construct(AdSpaceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $list = $this->repository->filter($request)->paginate();

        $data = [
            'list' => $list,
            'class_type' => self::CLASS_TYPE,
            'display_type' => self::DISPLAY_TYPE,
        ];

        return view('backend.ad_space.index')->with($data);
    }

    public function edit($id)
    {
        $data = [
            'space' => AdSpace::findOrFail($id),
            'display_type' => self::DISPLAY_TYPE,
        ];

        return view('backend.ad_space.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();

        $data = AdSpace::findOrFail($id);

        $data->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }
}
