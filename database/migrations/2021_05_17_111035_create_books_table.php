<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->string('author')->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->tinyInteger('end')->default(0)->comment('完结: 1=完结, -1=未完结');
            $table->string('vertical_cover')->comment('竖向封面');
            $table->string('horizontal_cover')->comment('横向封面');
            $table->tinyInteger('type')->default(0)->comment('类型: 1=日漫, 2=韩漫');
            $table->tinyInteger('status')->default(0)->comment('状态: 1=上架, -1=下架');
            $table->tinyInteger('charge')->default(0)->comment('收费状态: 1=VIP, -1=免费');
            $table->tinyInteger('review')->default(0)->comment('审核状态: 0=待审核, 1=审核成功, 2=审核失败, 3=屏蔽, 4=未审核');
            $table->tinyInteger('operating')->default(0)->comment('添加方式: 1=手动, 2=自动');
            $table->timestamps();
            $table->softDeletes();

            $table->index('end');
            $table->index('type');
            $table->index('status');
            $table->index('charge');
            $table->index('review');
            $table->index('operating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}