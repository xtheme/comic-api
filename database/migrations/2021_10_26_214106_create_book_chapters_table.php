<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('book_id')->index('book_id');
            $table->unsignedInteger('episode')->comment('章节');
            $table->string('title')->comment('标题');
            $table->text('json_images')->comment('JSON 图片');
            $table->tinyInteger('status')->default(1)->index('status')->comment('上架: 1=上架, 0=下架');
            $table->tinyInteger('price')->default(0)->index('price')->comment('售价 (金币)');
            $table->tinyInteger('operating')->default(0)->index('operating')->comment('添加方式: 1=人工, 2= 爬虫');
            $table->integer('view_counts')->default(0)->comment('访问数');
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
        Schema::dropIfExists('book_chapters');
    }
}
