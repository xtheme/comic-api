<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_albums', function (Blueprint $table) {
            $table->id();
            $table->string('source_platform', 10)->default('')->comment('來源平台')->index();
            $table->string('source_id', 50)->default('')->comment('來源編號')->index();
            $table->string('title')->comment('名称');
            $table->string('model')->comment('模特兒');
            $table->string('category')->nullable()->comment('分類');
            $table->integer('images_count')->default(0)->comment('圖片數量');
            $table->string('raw_cover')->default('')->comment('原始封面图');
            $table->json('raw_thumbs')->nullable()->comment('原始縮圖');
            $table->json('raw_images')->nullable()->comment('原始圖片');
            $table->boolean('process')->default(1)->comment('流程')->index();
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
        Schema::dropIfExists('resource_albums');
    }
}
