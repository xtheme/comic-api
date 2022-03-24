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
            $table->string('hls')->default('')->comment('影片路徑');
            $table->string('author')->default('')->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->unsignedTinyInteger('ribbon')->default(0)->comment('角标: 0=无, 1=限时免费, 2=会员抢先')->index();
            $table->boolean('status')->default(0)->comment('状态: 0=下架, 1=上架')->index();
            $table->tinyInteger('price')->default(0)->comment('售价 (金币)')->index();
            $table->unsignedTinyInteger('country')->default(0)->comment('国家')->index();
            $table->string('length', 30)->default('')->comment('影片长度');
            $table->boolean('mosaic')->default(0)->comment('馬賽克: 0=有码, 1=无码')->index();
            $table->boolean('style')->default(0)->comment('拍摄类型: 0=专业拍摄, 1=偷拍, 2=自拍, 3=业务拍摄')->index();
            $table->unsignedTinyInteger('subtitle')->default(0)->comment('字幕类型: 0=无, 1=中文, 2=英文, 3=中英文, 4=其他')->index();
            $table->unsignedInteger('view_counts')->default(0)->comment('访问数');
            $table->unsignedInteger('collect_counts')->default(0)->comment('收藏数');
            $table->string('number', 100)->nullable()->comment('番号')->index();
            $table->string('producer', 100)->nullable()->comment('制作商');
            $table->string('release_date', 10)->nullable()->comment('发行时间');
            $table->string('actor', 100)->nullable()->comment('演员');
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
        Schema::dropIfExists('videos');
    }
}
