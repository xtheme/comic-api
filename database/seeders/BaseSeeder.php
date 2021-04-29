<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * 添加视频广告位
     *
     * @param $name   广告名称
     * @param $class  广告位分类 (video：视频，comics：漫画)
     */
    protected function addAdSpace($name , $class)
    {

        $exists = DB::table('ad_spaces')->where('name', $name)->exists();

        if (!$exists) {
            $data = [
                'name' => $name,
                'remark' => '',
                'status' => 1,
                'sdk' => -1,
                'class' => $class,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('ad_spaces')->insert($data);
        }
    }
}
