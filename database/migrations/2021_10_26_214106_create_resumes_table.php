<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedInteger('admin_id')->comment('经纪人')->index();
            $table->unsignedInteger('province_id')->default(0)->comment('省id')->index();
            $table->unsignedInteger('city_id')->default(0)->comment('城市id')->index();
            $table->unsignedInteger('area_id')->default(0)->comment('区县id')->index();
            $table->string('nickname')->nullable()->comment('昵称');
            $table->integer('birth_year')->nullable()->comment('出生年');
            $table->integer('cup')->nullable()->comment('罩杯');
            $table->integer('height')->nullable()->comment('身高');
            $table->integer('weight')->nullable()->comment('体重');
            $table->string('price')->nullable()->comment('价位');
            $table->json('service')->nullable()->comment('服务项目');
            $table->string('schedule')->comment('班表');
            $table->string('contact')->comment('联络方式');
            $table->string('cover')->comment('封面');
            $table->string('video')->comment('视频');
            $table->json('album')->nullable()->comment('相簿');
            $table->unsignedInteger('view_counts')->default(0)->comment('访问数');
            $table->unsignedInteger('collect_counts')->default(0)->comment('收藏数');
            $table->unsignedInteger('like_counts')->default(0)->comment('点赞数');
            $table->unsignedInteger('dislike_counts')->default(0)->comment('倒赞数');
            $table->unsignedInteger('sales_volume')->default(0)->comment('销售量');
            $table->integer('point')->default(5)->comment('解锁点数');
            $table->unsignedTinyInteger('verify')->nullable()->default(0)->comment('认证: 0=否, 1=是');
            $table->unsignedTinyInteger('status')->default(0)->comment('上架: 1=上架, 0=下架');
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
        Schema::dropIfExists('resumes');
    }
}
