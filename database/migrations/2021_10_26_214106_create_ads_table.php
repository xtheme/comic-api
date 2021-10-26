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
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('space_id')->comment('廣告位id');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('url', 200)->nullable()->comment('鏈結地址');
            $table->string('banner', 200)->comment('廣告圖');
            $table->tinyInteger('status')->comment('狀態 [-1:下架,1:上架]');
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
        Schema::dropIfExists('ads');
    }
}
