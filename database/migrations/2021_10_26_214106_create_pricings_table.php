<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type')->default('')->comment('方案类型: 一般充值=charge, 包月=vip');
            $table->string('name')->default('')->comment('方案名稱');
            $table->text('description')->nullable()->comment('方案描述');
            $table->string('label')->default('')->comment('标签');
            $table->decimal('price', 10)->comment('售价 (RMB)');
            $table->decimal('list_price', 10)->comment('牌价 (原价)');
            $table->smallInteger('coin')->default(0)->comment('金幣數');
            $table->smallInteger('gift_coin')->default(0)->comment('加贈金幣數');
            $table->smallInteger('days')->default(0)->comment('VIP天数');
            $table->smallInteger('gift_days')->default(0)->comment('加贈VIP天數');
            $table->tinyInteger('target')->default(0)->comment('目標客群: 0=全用戶, 1=首存用户, 2=續約用戶');
            $table->tinyInteger('status')->default(0)->index('status')->comment('状态: 0=禁用, 1=啟用');
            $table->smallInteger('sort')->default(0)->comment('显示排序');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricings');
    }
}
