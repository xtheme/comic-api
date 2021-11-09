<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdController extends Controller
{
    /**
     * 查询廣告位底下的廣告列表
     */
    public function space(Request $request, $id)
    {
        $spaces = AdSpace::when($id, function (Builder $query, $id) {
            return $query->where('id', $id)->orWhere('name', $id);
        })->where('status', 1)->get();

        $data = $spaces->map(function ($space) use ($request) {
            return [
                'id' => $space->id,
                'name' => $space->name,
                'display' => $space->display,
                'ads' => $space->ads,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
