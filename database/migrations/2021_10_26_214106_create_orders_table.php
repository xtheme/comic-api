<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['charge', 'vip'])->index('type')->comment('方案类型');
            $table->string('order_no', 64)->index('order_no')->comment('訂單號');
            $table->integer('user_id')->index('user_id')->comment('用户ID');
            $table->integer('app_id')->default(0)->index('app_id')->comment('商戶ID');
            $table->integer('channel_id')->default(0)->index('channel_id')->comment('用戶來源渠道ID');
            $table->decimal('amount', 10)->comment('订单金额');
            $table->string('currency', 8)->default('CNY')->comment('貨幣iso code');
            $table->json('plan_options')->comment('支付方案');
            $table->integer('payment_id')->comment('支付渠道ID');
            $table->string('transaction_id', 64)->nullable()->index('transaction_id')->comment('第三方訂單號');
            $table->dateTime('transaction_at')->nullable()->comment('订单交易时间, 金流方回传');
            $table->tinyInteger('status')->default(0)->index('status')->comment('状态, -1=支付失败, 0=待处理, 1=支付成功');
            $table->string('ip')->nullable();
            $table->string('platform', 8)->nullable()->index('platform')->comment('平台');
            $table->string('version')->nullable()->comment('平台版本号');
            $table->tinyInteger('first')->default(0)->comment('用戶首储=1');
            $table->timestamps()->comment('订单更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
