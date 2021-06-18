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
        if (!Schema::hasTable('video_series')) {
            Schema::create('video_series', function (Blueprint $table) {
                $table->id();
                $table->integer('video_id')->unsigned()->index();
                $table->integer('episode')->unsigned()->comment('集数');
                $table->string('title')->comment('影集标题');
                $table->boolean('status')->default(0)->index()->comment('上架: 1=上架, -1=下架');
                $table->boolean('charge')->default(0)->index()->comment('收费: 1=VIP, -1=免费');
                $table->string('video_domain_id')->index()->comment('视频域名');
                $table->string('link')->comment('视频链结');
                $table->string('length')->comment('视频长度');
                $table->timestamps();
            });
        }
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
