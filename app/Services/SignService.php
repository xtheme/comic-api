<?php

namespace App\Services;

use App\Models\Config;
use App\Models\Sign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SignService
{

    /**
     * 積分config
     *
     */
    public function scoreList()
    {
        $configs = explode(';', getConfig('sign_config'));
        $score_list = [];
        foreach ($configs as $k => $v) {
            $arr = explode("|", $v);
            $score_list[$k] = $arr[1];
        }
        return $score_list;
    }

    /**
     * 查詢昨天今天簽到記錄
     *
     */
    public function days()
    {

        $signs = request()->user->signs()->select(DB::raw('DATE(created_at) as date'))->whereBetween(DB::raw('DATE(created_at)'), [date('Y-m-d', strtotime('-1 day')), date('Y-m-d')]);

        return $signs;
    }

    /**
     * 写入签到
     * @return array
     */
    public function ins_sign($sign_days)
    {
        $data = [
            'user_id' => request()->user->id,
            'created_at' => Carbon::now(),
        ];

        Sign::insert($data);

        $days = ($sign_days === 7) ? 1 : $sign_days + 1;


        //查詢此次簽到分數
        $score = $this->score_get($days);

        request()->user->update([
            'score' => DB::raw("score + {$score}"),
            'sign_days' => $days
        ]);

        return ['score' => $score, 'days' => $days];
    }

    /**
     * 積分查询需要的分数
     * @param $day //取第几天的分数
     *
     * @return int
     */
    public function score_get($day)
    {
        $scoreList = $this->scoreList();

        $score = 0;
        $day--;
        foreach ($scoreList as $k => $v) {
            if ($k == $day) {
                $score = $v;
                break;
            }
        }
        return $score;
    }

}
