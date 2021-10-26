<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('channel_id')->default(0);
            $table->string('description', 100)->comment('渠道備註');
            $table->tinyInteger('safe_landing')->default(1)->comment('安全落地頁, 1=啟用, 0=關閉');
            $table->integer('register_count')->default(0)->comment('注册用户数量');
            $table->integer('register_wap_count')->default(0)->comment('WAP 註冊數');
            $table->integer('register_app_count')->default(0)->comment('APP 註冊數');
            $table->integer('recharge_count')->default(0)->comment('累积充值数');
            $table->integer('recharge_wap_count')->default(0)->comment('WAP 小計充值数');
            $table->integer('recharge_app_count')->default(0)->comment('APP 小計充值数');
            $table->decimal('recharge_amount', 10)->default(0.00)->comment('渠道指定日期注册用户累积充值金额');
            $table->decimal('recharge_wap_amount', 10)->default(0.00)->comment('渠道指定日期注册用户累积充值WAP金额');
            $table->decimal('recharge_app_amount', 10)->default(0.00)->comment('渠道指定日期注册用户累积充值APP金额');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
