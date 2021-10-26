<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('标题');
            $table->string('icon')->comment('图标');
            $table->unsignedTinyInteger('target')->default(1)->comment('跳转方式: 1=內部路由, 2=另開浏览器');
            $table->integer('filter_id')->default(0)->comment('篩選條件');
            $table->string('link');
            $table->tinyInteger('sort')->comment('排序值: 数字越大越靠前');
            $table->tinyInteger('status')->comment('图标 URL');
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
        Schema::dropIfExists('navigation');
    }
}
