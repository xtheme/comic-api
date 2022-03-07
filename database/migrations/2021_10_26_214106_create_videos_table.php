<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('名称');
            $table->string('cover')->default('')->comment('封面图');
            $table->string('storage_path')->default('')->comment('影片路徑');
            $table->string('author')->default('')->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->unsignedTinyInteger('ribbon')->default(0)->comment('角标: 0=无, 1=限时免费, 2=会员抢先')->index();
            $table->boolean('status')->default(0)->comment('状态: 0=下架, 1=上架')->index();
            $table->string('country', 30)->default('')->comment('国家');
            $table->string('length', 30)->default('')->comment('影片长度');
            $table->boolean('mosaic')->default(0)->comment('馬賽克: 0=有码, 1=无码');
            $table->boolean('style')->default(0)->comment('拍摄类型: 0=专业拍摄, 1=偷拍, 2=自拍, 3=业务拍摄');
            $table->unsignedTinyInteger('subtitle')->default(0)->comment('字幕类型: 0=无, 1=中文, 2=英文, 3=中英文, 4=其他');
            $table->unsignedInteger('views')->default(0)->comment('播放次数');
            $table->string('number', 100)->default('')->comment('播放次数');
            $table->string('producer', 100)->default('')->comment('制作商');
            $table->string('actor', 255)->default('')->comment('主演');
            $table->string('published_at', 10)->default('')->comment('发行时间');
            $table->timestamps();
            $table->string('source_platform', 10)->default('')->comment('來源平台')->index();
            $table->unsignedBigInteger('source_id')->default(0)->comment('來源編號')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}