<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPurchaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_purchase_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->index('user_id')->comment('用戶ID');
            $table->integer('app_id')->default(0)->comment('商戶ID');
            $table->integer('channel_id')->default(0)->comment('來源渠道ID');
            $table->string('type')->comment('類型');
            $table->string('item_model')->index('type')->comment('商品模型');
            $table->integer('item_id')->nullable()->comment('商品ID');
            $table->smallInteger('item_price')->default(0)->comment('商品售價');
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
        Schema::dropIfExists('user_purchase_logs');
    }
}
