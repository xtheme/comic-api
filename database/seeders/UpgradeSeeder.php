<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpgradeSeeder extends BaseSeeder
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
        $this->addAdType('动画详情页广告');

    }
}
