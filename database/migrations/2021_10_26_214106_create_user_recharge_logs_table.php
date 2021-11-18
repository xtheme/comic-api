<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRechargeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_recharge_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->index('user_id')->comment('用戶ID');
            $table->integer('app_id')->default(0)->comment('商戶ID');
            $table->integer('channel_id')->default(0)->comment('來源渠道ID');
            $table->enum('type', ['charge', 'vip', 'gift', 'penalty'])->comment('類型');
            $table->integer('admin_id')->nullable()->index('admin_id')->comment('管理員ID (人工補單)');
            $table->integer('order_id')->nullable()->comment('訂單ID');
            $table->string('order_no', 64)->comment('訂單號');
            $table->smallInteger('coin')->default(0)->comment('金幣數');
            $table->smallInteger('gift_coin')->default(0)->comment('加贈金幣數');
            $table->smallInteger('days')->default(0)->comment('VIP天数');
            $table->smallInteger('gift_days')->default(0)->comment('加贈VIP天數');
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
        Schema::dropIfExists('user_recharge_logs');
    }
}
