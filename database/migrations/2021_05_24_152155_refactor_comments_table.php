<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_clone', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter_id');
            $table->integer('user_id');
            $table->text('content');
            $table->tinyInteger('status')->comment('评论状态 [1:正常，-1:隐藏]');
            $table->integer('likes')->comment('点赞数量');
            $table->timestamps();

            $table->index('chapter_id', 'chapter_id');
            $table->index('user_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_clone');
    }
}
