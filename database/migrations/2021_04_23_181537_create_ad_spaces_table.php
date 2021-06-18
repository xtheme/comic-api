<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ad_spaces')) {
            Schema::create('ad_spaces', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique()->comment('名称');
                $table->text('remark')->comment('备注');
                $table->tinyInteger('status')->default(-1)->comment('上下架表示 [-1:下架,1:上架]');
                $table->tinyInteger('sdk')->default(-1)->comment('接入廣告sdk [-1:否,1:是]');
                $table->string('class', 20)->comment('广告位分类 [video:视频,comic:漫画]');
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
        Schema::dropIfExists('ad_spaces');
    }
}
