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
            $table->integer('episode')->unsigned()->comment('集数');
            $table->string('title')->comment('影集标题');
            $table->boolean('vip')->default(0)->comment('资格: 1=VIP, -1=免费');
            $table->boolean('status')->default(0)->comment('状态: 1=上架, -1=下架');
            $table->string('video_domain_id')->comment('视频域名');
            $table->string('link')->comment('视频链结');
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
