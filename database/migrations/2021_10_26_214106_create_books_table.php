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
            $table->bigIncrements('id');
            $table->string('title')->comment('标题');
            $table->string('author')->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->tinyInteger('end')->default(0)->index('end')->comment('完结: 1=完结, 0=未完结');
            $table->string('cover')->comment('封面');
            $table->tinyInteger('type')->default(1)->index('type')->comment('类型: 1=日漫, 2=韩漫');
            $table->tinyInteger('status')->default(1)->index('status')->comment('状态: 1=上架, 0=下架');
            $table->tinyInteger('review')->default(1)->index('review')->comment('审核状态: 0=待审核, 1=审核成功, 2=审核失败, 3=屏蔽, 4=未审核');
            $table->tinyInteger('operating')->default(1)->index('operating')->comment('添加方式: 1=人工, 2= 爬虫');
            $table->unsignedInteger('view_counts')->default(0)->comment('访问数');
            $table->unsignedInteger('collect_counts')->default(0)->comment('收藏数');
            $table->string('keywords')->nullable()->comment('标签');
            $table->string('source_platform', 10)->default('')->comment('來源平台')->index();
            $table->unsignedBigInteger('source_id')->default(0)->comment('來源編號')->index();
            $table->timestamps();
            $table->softDeletes();
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
