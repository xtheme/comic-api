<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 191)->nullable()->comment('分類: 漫畫或視頻');
            $table->integer('filter_id')->default(0)->comment('篩選條件');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('spotlight')->comment('聚焦数量');
            $table->integer('row')->comment('行数量');
            $table->integer('limit')->comment('筆數');
            $table->json('properties')->nullable();
            $table->tinyInteger('status')->default(1)->comment('狀態: 0=下架,1=上架');
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
        Schema::dropIfExists('topics');
    }
}
