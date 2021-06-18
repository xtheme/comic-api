<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('book_chapters')) {
            Schema::create('book_chapters', function (Blueprint $table) {
                $table->id();
                $table->integer('book_id')->unsigned();
                $table->integer('episode')->unsigned()->comment('集数');
                $table->string('title')->comment('标题');
                // $table->string('cover')->comment('封面');
                $table->text('content')->comment('HTML 图片');
                $table->text('json_images')->comment('JSON 图片');
                // $table->boolean('status')->default(0)->comment('状态: 0 不启用  1启用');
                $table->tinyInteger('status')->default(0)->comment('上架: 1=上架, -1=下架');
                // $table->boolean('vip')->default(0)->comment('资格: 0=非vip章节,1=vip章节,2付费章节');
                $table->tinyInteger('charge')->default(0)->comment('收费: 1=VIP, -1=免费');
                $table->tinyInteger('review')->default(0)->comment('审核: 0=待审核, 1=审核成功, 2=审核失败, 3=屏蔽, 4=未审核');
                $table->tinyInteger('operating')->default(0)->comment('添加方式: 1=手动, 2=自动');
                $table->timestamps();
                $table->softDeletes();

                $table->index('book_id');
                $table->index('status');
                $table->index('charge');
                $table->index('review');
                $table->index('operating');
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
        Schema::dropIfExists('book_chapters');
    }
}
