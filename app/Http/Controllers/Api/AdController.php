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

        $spaces = $this->repository->ads($request, $id)->get();

        $data = $spaces->map(function($space) use ($request) {
            // $xad_id = null;
            // if ($space->sdk == 1) {
            //     $xad_id = ($request->header('platform') == 1) ? $space->xad_android_id : $space->xad_ios_id;
            // }
            return [
                'id' => $space->id,
                'name' => $space->name,
                // 'remark' => $space->remark,
                // 'status' => $space->status,
                // 'sdk' => $space->sdk,
                // 'xad_id' => $xad_id,
                // 'class' => $space->class,
                'display' => $space->display,
                // 'created_at' => $space->created_at->format('Y-m-d H:i:s'),
                'ads' => $space->ads
            ];
        })->toArray();


        return Response::jsonSuccess(__('api.success'), $data);
    }
}
