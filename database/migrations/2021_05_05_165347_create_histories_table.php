<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->integer('major_id')->comment('主id');
            $table->integer('minor_id')->comment('从id');
            $table->integer('user_vip')->comment('是否vip [-1:非vip,1:vip]');
            $table->integer('user_id')->comment('用户id');
            $table->string('type', 20)->comment('种类 [浏览:visit,播放:play,收藏:favorites]');
            $table->string('class', 20)->comment('分类 [动画:video,漫画:comic]');
            $table->timestamp('created_at')->comment('创建时间');
            $table->primary([
                'major_id',
                'minor_id',
                'user_vip',
                'user_id',
                'type',
                'class',
            ], 'primary_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
