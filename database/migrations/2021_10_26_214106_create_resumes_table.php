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
            $table->string('name')->nullable()->comment('模特名');
            $table->integer('age')->nullable()->comment('年龄');
            $table->integer('sale_price')->nullable()->comment('平台销售价格');
            $table->string('price')->nullable()->comment('价格');
            $table->integer('province_id')->nullable()->comment('地区');
            $table->integer('city_id')->nullable()->comment('城市');
            $table->string('qq')->nullable();
            $table->string('wechat')->nullable();
            $table->string('phone')->nullable();
            $table->text('tag')->nullable()->comment('标签');
            $table->text('profile')->nullable()->comment('简介
');
            $table->integer('images_count')->nullable()->default(0);
            $table->integer('tag_count')->nullable()->default(0);
            $table->integer('sold_count')->nullable()->default(0);
            $table->integer('complaint_count')->nullable()->default(0);
            $table->tinyInteger('state')->nullable()->default(1)->comment('1上架  2下架');
            $table->string('operator', 45)->nullable()->comment('最后操作人');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->json('pictures')->nullable();
            $table->tinyInteger('approve')->nullable()->default(0)->comment('0不认证 1认证');
            $table->string('tt')->nullable()->default('')->comment('tt联系');
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
