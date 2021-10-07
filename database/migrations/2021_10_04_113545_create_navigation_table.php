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
            $table->id();
            $table->string('title')->comment('导航名称');
            $table->string('icon')->comment('图标');
            $table->string('uri');
            $table->tinyInteger('target')->unsigned()->default(0)->comment('跳转方式: 0=跳外部浏览器, 1=内置浏览器');
            $table->tinyInteger('sort')->unsigned()->default(0)->comment('排序值: 数字越大越靠前');
            $table->tinyInteger('status')->comment('状态: 0=下架, 1=上架');
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
