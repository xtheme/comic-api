<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_visit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->index('user_id')->comment('用戶ID');
            $table->string('type')->index('type')->comment('類型');
            $table->string('item_model')->comment('商品模型');
            $table->integer('item_id')->nullable()->comment('商品ID');
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
        Schema::dropIfExists('user_visit_logs');
    }
}
