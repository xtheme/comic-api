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

    public function arrangeData($ad_space)
    {
        $platform = request()->header('platform');
        
        $ads = $ad_space->ads()->whereIn('platform', [$platform , '-1'])->orderByDesc('sort')->get()->map(function($item) {
            return [
                'id'            => $item->id,
                'sort'          => $item->sort,
                'space_id'      => $item->space_id,
                'name'          => $item->name,
                'image'         => image_thumb($item->image),
                'url'           => $item->url,
                'status'        => $item->status,
                'platform'      => $item->platform,
                'jump_type'     => $item->jump_type,
                'show_time'     => $item->show_time
            ];
        })->toArray();

        return $ads;
    }


    /**
     * 查询廣告位底下的廣告列表
     */
    public function space(Request $request, $id)
    {

        $request->merge([
            'id'    => $id,
            'status'    => 1,
        ]);

        $ad_space = $this->repository->filter($request)->get()->first();

        $ad_space['ads'] = $this->arrangeData($ad_space);

        
        return Response::jsonSuccess('返回成功', $ad_space);
    }
}
