<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('名称');
            $table->string('author')->nullable()->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->string('cover')->nullable()->comment('封面图');
            $table->tinyInteger('ribbon')->unsigned()->default(0)->comment('角标: 0=无, 1=限时免费, 2=会员抢先');
            $table->boolean('status')->default(0)->comment('状态: 1=上架, -1=下架');
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
        Schema::dropIfExists('videos');
    }
}
