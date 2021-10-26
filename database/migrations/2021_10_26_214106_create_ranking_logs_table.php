<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->comment('類型');
            $table->string('item_model')->comment('商品模型');
            $table->integer('item_id')->index('book_id');
            $table->integer('views');
            $table->year('year')->index('year');
            $table->tinyInteger('month');
            $table->index(['year', 'month'], 'date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranking_logs');
    }
}
