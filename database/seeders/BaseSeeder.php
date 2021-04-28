<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * 添加视频广告位
     *
     * @param $name
     */
    protected function addAdType($name)
    {
        $exists = DB::table('video_ad_spaces')->where('name', $name)->exists();

        if (!$exists) {
            $data = [
                'name' => $name,
                'remark' => '',
                'status' => 1,
                'if_sdk_ads' => -1,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('video_ad_spaces')->insert($data);
        }
    }
}
