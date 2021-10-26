<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_tags', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable()->comment('标签名');
            $table->string('desc', 250)->nullable()->comment('标签说明');
            $table->integer('cate_id')->nullable()->comment('标签分类id');
            $table->integer('hot')->default(0)->index('video_tags_hot_index')->comment('热度');
            $table->string('pic', 250)->nullable()->default('')->comment('标签图标');
            $table->unsignedInteger('hot_plus')->default(0)->comment('热度增加：运营指定');
            $table->enum('status', ['0', '1'])->nullable()->default('1')->comment('状态（1启用，0 禁用）');
            $table->integer('sort')->nullable()->default(0)->comment('排序');
            $table->timestamps();
            $table->integer('total')->nullable()->default(0)->comment('标签下关联的影片数量');
            $table->index(['id', 'name', 'cate_id'], 'tags_id_name_catid_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_tags');
    }
}
