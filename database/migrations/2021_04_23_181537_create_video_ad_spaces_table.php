<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoAdSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_ad_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 100)->comment('名称');
            $table->text('remark')->comment('备注');
            $table->tinyInteger('status')->default(-1)->comment('上下架表示 [-1:下架,1:上架]');
            $table->tinyInteger('if_sdk_ads')->default(-1)->comment('接入廣告sdk [-1:否,1:是]');
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
        Schema::dropIfExists('video_ad_spaces');
    }
}
