<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('video_visits')) {
            Schema::create('video_visits', function (Blueprint $table) {
                $table->id();
                $table->integer('video_id');
                $table->integer('series_id');
                $table->integer('user_id');
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
        Schema::dropIfExists('video_visits');
    }
}
