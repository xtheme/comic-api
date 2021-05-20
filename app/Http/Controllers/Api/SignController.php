<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SignController extends Controller
{
    protected $signService;

    public function __construct(SignService $signService)
    {
        $this->signService = $signService;
    }

    /**
     * 签到详情
     *
     * @param Request $request
     *
     * @return Response
     */
    public function signDetail(Request $request)
    {

        $sign_days = $this->signService->days();

        $today_sign = $sign_days->pluck('date')->contains(date('Y-m-d'));


        $exists = $sign_days->exists();

        $days = 0;
        if ($exists) {
            $days = $request->user->sign_days;
        }

        $score_list = $this->signService->scoreList();

        $data = [
            'days' => $days,
            'today_sign' => $today_sign,
            'score_list' => $score_list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

    /**
     * 签到
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sign(Request $request)
    {

        $sign_days = $this->signService->days();

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d'));

        if ($exists) {
            return Response::jsonError('今日已签到');
        }

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d', strtotime('-1 day')));

        $days = 0;
        if ($exists) {
            $days = request()->user->sign_days;
        }

        //寫入簽到
        $data = $this->signService->ins_sign($days);

        $data = [
            'score' => $data['score'],
            'days' => $data['days']
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
