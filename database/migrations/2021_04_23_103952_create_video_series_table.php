<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_series', function (Blueprint $table) {
            $table->id();
            $table->integer('video_id')->unsigned();
            $table->integer('episode')->unsigned()->comment('第几集');
            $table->string('title')->comment('名称');
            $table->tinyInteger('free')->unsigned()->default(0)->comment('0=收费, 1=免费');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('状态');
            $table->string('video_domain_id')->comment('视频域名');
            $table->string('link')->comment('视频域名');
            $table->string('length')->comment('视频长度');
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
        Schema::dropIfExists('video_series');
    }
}
