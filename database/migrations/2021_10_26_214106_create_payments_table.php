<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('渠道名稱');
            $table->string('url')->nullable()->default('')->comment('渠道後台網址');
            $table->string('app_id')->default('')->comment('渠道商戶');
            $table->string('app_key')->default('')->comment('渠道金鑰');
            $table->string('button_text')->default('')->comment('前端按鈕文字');
            $table->string('button_icon')->default('')->comment('前端按鈕圖示');
            $table->string('button_target')->default('')->comment('前端轉跳方式');
            $table->tinyInteger('fee_percentage')->default(0)->comment('手續費%');
            $table->string('sdk')->nullable()->default('')->comment('SDK');
            $table->unsignedInteger('daily_limit')->default(0)->comment('每日限額');
            $table->json('pay_options')->comment('支付配置');
            $table->json('order_options')->comment('查詢訂單配置');
            $table->tinyInteger('status')->default(0)->comment('状态: 0=禁用, 1=啟用');
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
        Schema::dropIfExists('payments');
    }
}
