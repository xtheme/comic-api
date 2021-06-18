<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoPlayLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('video_play_logs')) {
            Schema::create('video_play_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('video_id');
                $table->integer('series_id');
                $table->integer('user_id');
                $table->integer('vip');
                $table->timestamps();

                $table->index('video_id');
                $table->index('series_id');
                $table->index('user_id');
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
        Schema::dropIfExists('video_play_logs');
    }
}
