<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComicResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comic_resources', function (Blueprint $table) {
            $table->id();
            $table->string('source_platform', 10)->default('')->comment('來源平台')->index();
            $table->unsignedBigInteger('source_id')->default(0)->comment('來源編號')->index();
            $table->enum('type', ['jp', 'kr', 'album'])->nullable()->comment('标签');
            $table->boolean('translate')->default(0)->comment('中文化: Y/N');
            $table->string('title')->comment('名称');
            $table->string('cover')->default('')->comment('封面图');
            $table->text('description')->nullable()->comment('简介');
            $table->string('keywords')->nullable()->comment('标签');
            $table->boolean('crawl_detail')->default(0)->comment('採集詳情: Y/N')->index();
            $table->boolean('crawl_image')->default(0)->comment('採集图片: Y/N')->index();
            $table->timestamps();

            $table->unique(['source_platform', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comic_resources');
    }
}
