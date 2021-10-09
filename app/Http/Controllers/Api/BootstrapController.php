<?php

namespace App\Http\Controllers\Api;

use App\Models\AdSpace;
use Illuminate\Http\Request;
use App\Models\BookReportType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;

class BootstrapController extends Controller
{
    /**
     * 查询廣告位底下的廣告列表
     */
    public function configs()
    {
        $data = [
            'app' => getConfigs('app'),
        ];

        return Response::jsonSuccess('返回成功', $data);
    }

    private function getAdSpaces()
    {
        $ad_spaces = AdSpace::all();

        return $ad_spaces->map(function ($space) {
            $xad_id = null;
            if ($space->sdk == 1) {
                $xad_id = (request()->header('platform') == 1) ? $space->xad_android_id : $space->xad_ios_id;
            }
            return [
                'id' => $space->id,
                'status' => $space->status,
                'sdk' => $space->sdk,
                'xad_id' => $xad_id
            ];
        });
    }

    private function getReportTypes()
    {
        $report_types = BookReportType::where('status' , 0)->orderByDesc('sort')->get();

        return $report_types->map(function ($report_type) {
            return [
                'id' => $report_type->id,
                'sort' => $report_type->sort,
                'name' => $report_type->name
            ];
        })->toArray();
    }
}
