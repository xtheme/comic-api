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
            $table->string('raw_cover')->default('')->comment('原始封面图');
            $table->string('new_cover')->default('')->comment('整新封面图');
            $table->string('author')->comment('作者');
            $table->text('description')->nullable()->comment('简介');
            $table->string('keywords')->nullable()->comment('标签');
            $table->boolean('process')->default(1)->comment('流程')->index();
            $table->integer('images_count')->default(0)->comment('圖片數量');
            $table->json('raw_images')->nullable()->comment('原始圖片');
            $table->json('new_images')->nullable()->comment('整新圖片');
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
