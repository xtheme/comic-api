<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('title' , 100)->comment('标题');
            $table->integer('sort' )->default(0)->comment('排序');
            $table->integer('spotlight')->comment('聚焦数量');
            $table->integer('row')->comment('每行几笔');
            $table->string('causer', 191)->nullable();
            $table->json('properties')->nullable();
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
        Schema::dropIfExists('blocks');
    }
}
