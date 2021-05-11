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
     * @param  Request  $request
     *
     * @return Response
     */
    public function signDetail(Request $request)
    {
        $sign = $request->user->sign_in;

        $yDay = strtotime(date('Y-m-d', strtotime('-1 day')) . "00:00:00");

        if (!empty($sign)) {
            $days = $sign->days;
        }

        //判斷數據是否從沒寫過 從0天開始計算   或是 昨天到目前都沒簽到 也算是從0開始
        if (empty($sign) || ($yDay >= $sign->addtime)) {
            $days = 0;
        }

        $score_list = $this->signService->scoreList();

        $data = [
            'days'       => $days,
            'score_list' => $score_list,
        ];

        return Response::jsonSuccess('返回成功', $data);
    }

    /**
     * 签到
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function sign(Request $request)
    {
        $sign = $request->user->sign_in; // 签到记录
        $today = strtotime(date('Y-m-d') . "00:00:00");
        $yesterday = strtotime(date('Y-m-d', strtotime('-1 day')) . "00:00:00");

        // 查询不到资料走写入資料流程
        if (empty($sign)) {
            $score = $this->signService->insertSign();

            // 刷新缓存暫無
            // $user = User::find($request->user->id);
            // $this->userService->updateUserCache($user);

            $data = [
                'score' => $score,
                'days'  => 1,
            ];

            return Response::jsonSuccess('签到成功,获得' . $score . '书币', $data);
        }

        if ($sign->addtime >= $today) {
            return Response::jsonError('今日已签到');
        }

        $days = $sign->days + 1;

        // 有资料决定走更新当笔数据的day为哪一天
        // 签到超过第七天 或是昨天没有签到 重置为第1天重新签到
        if ($sign->days + 1 > 7 || ($yesterday >= $sign->addtime)) {
            $days = 1;
        }

        $score = $this->signService->updateSign($days);

        // 刷新缓存暫無
        // $user = User::find($request->user->id);
        // $this->userService->updateUserCache($user);

        $data = [
            'score' => $score,
            'days'  => $days,
        ];

        return Response::jsonSuccess('签到成功,获得' . $score . '书币', $data);
    }
}
