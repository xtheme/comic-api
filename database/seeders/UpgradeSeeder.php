<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpgradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=UpgradeSeeder
     *
     * @return void
     */
    public function run()
    {
        // 升级添加广告位
        $this->addAdSpace('动画详情页广告' , 'video');

        // 升级 CDN 域名
        $this->addCdn();

    }

    /**
     * 添加视频广告位
     *
     * @param  string  $name  广告名称
     * @param  string  $class  广告位分类 (video：视频，comics：漫画)
     */
    protected function addAdSpace(string $name, string $class)
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

    protected function addCdn()
    {
        $exists = DB::table('video_domains')->where('title', '茄子線上和UAT共用域名')->exists();

        if (!$exists) {
            $data = [
                'title' => '茄子線上和UAT共用域名',
                'domain' => 'https://qzvd-blibli4.6b0e3d.com',
                'encrypt_domain' => 'https://qzvd-hw.testzone.cn',
                'remark' => '',
                'sort' => 0,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('video_domains')->insert($data);
        }
    }
}
