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
            $table->integer('id')->autoIncrement();
            $table->integer('payment_id')->default(0)->comment('渠道代號');
            $table->date('date')->comment('日期');
            $table->integer('recharge_count')->default(0)->comment('累计充值数');
            $table->decimal('recharge_amount', 10, 2)->default(0.00)->comment('累计充值金额');
            $table->integer('wap_new_count')->default(0)->comment('WAP 新用戶充值数');
            $table->integer('app_new_count')->default(0)->comment('APP 新用戶充值数');
            $table->integer('new_count')->default(0)->comment('新用戶充值数');
            $table->integer('wap_renew_count')->default(0)->comment('WAP 老用戶充值数');
            $table->integer('app_renew_count')->default(0)->comment('APP 老用戶充值数');
            $table->integer('renew_count')->default(0)->comment('老用戶充值数');
            $table->decimal('wap_new_amount', 10, 2)->default(0.00)->comment('WAP 新用戶充值金额');
            $table->decimal('app_new_amount', 10, 2)->default(0.00)->comment('APP 新用戶充值金额');
            $table->decimal('new_amount', 10, 2)->default(0.00)->comment('新用戶充值金额');
            $table->decimal('wap_renew_amount', 10, 2)->default(0.00)->comment('WAP 老用戶充值金额');
            $table->decimal('app_renew_amount', 10, 2)->default(0.00)->comment('APP 老用戶充值金额');
            $table->decimal('renew_amount', 10, 2)->default(0.00)->comment('老用戶充值金额');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
