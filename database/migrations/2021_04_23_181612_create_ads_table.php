<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ads')) {
            Schema::create('ads', function (Blueprint $table) {
                $table->id();
                $table->integer('space_id')->comment('廣告位id');
                $table->string('name' , 100)->comment('广告名称 ');
                $table->integer('sort')->default(0)->comment('排序');
                $table->tinyInteger('platform')->comment('所屬平台 [1:安卓,2:IOS]');
                $table->tinyInteger('jump_type')->comment('跳转类型 [1:内置浏览器,2:App下载,3:外部浏览器,4:站内充值页,5:不跳转]');
                $table->string('url' , 200)->nullable()->comment('鏈結地址');
                $table->integer('show_time')->default(0)->comment('顯示時間，0為不設置');
                $table->string('image' , 200)->comment('廣告圖');
                $table->tinyInteger('status')->comment('狀態 [-1:下架,1:上架]');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
