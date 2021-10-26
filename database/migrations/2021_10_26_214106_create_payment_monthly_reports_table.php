<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMonthlyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_monthly_reports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('payment_id')->default(0)->index('search_key')->comment('支付渠道');
            $table->date('date')->comment('日期 (月份)');
            $table->integer('register_app_count')->default(0)->comment('APP 註冊數');
            $table->integer('register_wap_count')->default(0)->comment('WAP 註冊數');
            $table->integer('recharge_count')->default(0)->comment('累积充值数');
            $table->integer('wap_new_count')->default(0)->comment('WAP 新用戶充值数');
            $table->integer('wap_renew_count')->default(0)->comment('WAP 老用戶充值数');
            $table->integer('wap_count')->default(0)->comment('WAP 小計充值数');
            $table->integer('app_new_count')->default(0)->comment('APP 新用戶充值数');
            $table->integer('app_renew_count')->default(0)->comment('APP 老用戶充值数');
            $table->integer('app_count')->default(0)->comment('APP 小計充值数');
            $table->decimal('recharge_amount', 10)->default(0.00)->comment('累积充值金额');
            $table->decimal('wap_new_amount', 10)->default(0.00)->comment('WAP 新用戶充值金额');
            $table->decimal('wap_renew_amount', 10)->default(0.00)->comment('WAP 老用戶充值金额');
            $table->decimal('wap_amount', 10)->default(0.00)->comment('WAP 小計充值金额');
            $table->decimal('app_new_amount', 10)->default(0.00)->comment('APP 新用戶充值金额');
            $table->decimal('app_renew_amount', 10)->default(0.00)->comment('APP 老用戶充值金额');
            $table->decimal('app_amount', 10)->default(0.00)->comment('APP 小計充值金额');
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
        Schema::dropIfExists('payment_monthly_reports');
    }
}
