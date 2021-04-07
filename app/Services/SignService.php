<?php

namespace App\Services;

use App\Models\Config;
use App\Models\Sign;

class SignService
{

    /**
     * 積分列表
     *
     */
    public function scoreList()
    {
        $getUserConfig = Config::where('code' , 'sign_config')->first();
        $sign = explode(';',$getUserConfig->content);
        $score_list = [];
        foreach($sign as $k=>$v){
            $arr = explode("|",$v);
            $score_list[$k] = $arr[1];
        }
        return $score_list;
    }

    /**
     * 写入签到
     * @return $score
     */
    public function insertSign()
    {
        $data = [
            'uid'       => request()->user->id ,
            'days'      => 1 ,
            'addtime'   => time() ,
        ];
        $score = $this->scoreGet();

        $sign = new Sign();
        foreach ($data as $key => $value) {
            $sign->$key = $value;
        }
        $sign->save();
        $sign->user()->increment('score',$score);

        return $score;
    }

    /**
     * 更新签到
     * @return $score
     */
    public function updateSign($days)
    {
        $data = [
            'days'      => $days ,
            'addtime'   => time() ,
        ];
        $score = $this->scoreGet($days);

        $sign = request()->user->signin;

        foreach ($data as $key => $value) {
            $sign->$key = $value;
        }

        $sign->save();
        $sign->user()->increment('score',$score);

        return $score;
    }


    /**
     * 積分查询需要的分数
     * @param $day   //取第几天的分数
     *
     */
    public function scoreGet($day = 1)
    {
        $scoreList = $this->scoreList();

        $score = 0;
        //進入的起始天數 -1  迴圈key從0開始比對
        $day--;
        foreach ($scoreList as $k=>$v){
            if($k == $day){
                $score=$v;
                break;
            }
        }
        return $score;
    }

}
