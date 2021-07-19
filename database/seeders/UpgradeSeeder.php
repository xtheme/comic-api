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

        $this->addAdSpace('入口-引导页广告' , 'other');

        $this->addAdSpace('首页-弹出广告' , 'other');

        $this->addAdSpace('漫画-首页bannner广告' , 'comics');

        $this->addAdSpace('漫画-章节列表广告' , 'comics');

        $this->addAdSpace('漫画-模块间广告' , 'comics');

        $this->addAdSpace('漫画-详情页广告' , 'comics');

        $this->addAdSpace('首页-推荐App' , 'other');

        $this->addAdSpace('漫画-栏目推荐更多广告' , 'comics');

        $this->addAdSpace('漫画-最新更新广告' , 'comics');

        $this->addAdSpace('漫画-历史导航广告' , 'comics');

        $this->addAdSpace('首页-活动界面' , 'other');

        $this->addAdSpace('首页-招商广告' , 'other');

        $this->addAdSpace('首页-文内浮层广告' , 'other');

        $this->addAdSpace('漫画-章节间广告' , 'comics');

        $this->addAdSpace('漫画-详情间广告' , 'comics');

        $this->addAdSpace('漫画-热门作品广告' , 'comics');

        $this->addAdSpace('漫画-详情间广告-头' , 'comics');

        $this->addAdSpace('漫画-详情间广告-尾' , 'comics');

        // 升级 CDN 域名
        $this->addCdn();

    }

    /**
     * 添加视频广告位
     *
     * @param  string  $name  广告名称
     * @param  string  $class  广告位分类 (video：视频，comics：漫画，other：其他)
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
                'display' => 1,
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
