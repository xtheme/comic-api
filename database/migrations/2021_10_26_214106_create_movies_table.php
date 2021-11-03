<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->integer('id', true)->comment('ID');
            $table->string('video_name')->comment('影片名称');
            $table->string('description')->nullable()->comment('影片描述');
            $table->string('url')->comment('影片地址');
            $table->integer('cdn_number')->nullable()->default(0)->comment('CDN编号');
            $table->string('preview_pics')->nullable()->comment('预览图');
            $table->string('vertical_cover')->nullable()->comment('竖封面图');
            $table->string('horizontal_cover')->nullable()->comment('横封面图');
            $table->string('movie_length', 30)->nullable()->default('null')->comment('影片长度');
            $table->string('country', 50)->nullable()->comment('国家');
            $table->integer('status')->nullable()->default(0)->comment('影片状态（0:待审核 1:发布 2:下架）');
            $table->integer('video_type')->nullable()->default(0)->comment('影片类型（0:有码 1：无码）');
            $table->integer('shooting_type')->nullable()->default(0)->comment('拍摄类型(0:专业拍摄 1:偷拍 2:自拍 3:业务拍摄)');
            $table->integer('subtitle_type')->nullable()->default(0)->comment('字幕类型(0:无 1:中文 2:英文 3:中英文 4:其他)');
            $table->integer('heat')->nullable()->default(0)->comment('热度');
            $table->integer('heat_plus')->nullable()->default(0)->comment('运营加分');
            $table->string('number', 100)->nullable()->comment('番号');
            $table->string('producer', 100)->nullable()->comment('制作商');
            $table->string('actor')->nullable()->comment('主演');
            $table->string('publish_time', 32)->nullable()->comment('发行时间');
            $table->string('tags')->nullable()->comment('标签');
            $table->timestamps();
            $table->unsignedInteger('views')->nullable()->default(0)->comment('播放次数');
            $table->index(['id', 'number'], 'movie_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
