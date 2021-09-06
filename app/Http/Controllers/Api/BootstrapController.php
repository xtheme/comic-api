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
    public function token(Request $request)
    {

    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function configs()
    {
        $data = [
            'encrypt_img' => getConfig('app', 'encrypt_img'), // if_encode_pic
            'encrypt_api' => getConfig('app', 'encrypt_api'), // encode_api_url
            'landing_page' => getConfig('app', 'landing_page'), // start_domain
            'share_url' => getConfig('share', 'url'), // share_url
            'share_title' => getConfig('share', 'title'), // app_share_url
            'share_qrcode' => asset('qrcode.png'), // qrcode_url
            'contact_email' => getConfig('service', 'email'), // contact_email
            'contact_phone' => getConfig('service', 'phone'), // contact_phone
            'ad_sdk' => getConfig('app', 'ad_sdk'), // if_sdk_ads
            'ad_spaces' => $this->getAdSpaces(), // use_sdk_ads_type
            'api_domains' => getConfig('app', 'api_domains'), // use_sdk_ads_type
            'sign_config' => getConfig('app', 'sign_config'), // use_sdk_ads_type
            'report_types' => $this->getReportTypes(), // use_sdk_ads_type
            'ranking' => getConfig('app', 'ranking'), //漫畫排行榜
            // 'if_sdk_ads'       => $this->web_config['if_sdk_ads'] ?? 0,
            // 'use_sdk_ads_type' => $use_sdk_ads_type,
            // 'ca'               => 0,
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
