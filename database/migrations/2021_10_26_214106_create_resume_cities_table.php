<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumeCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('p_id')->default(0)->comment('父节点id');
            $table->string('name')->default('\\\'\\\'')->comment('节点名称');
            $table->integer('layer')->default(0)->comment('层级');
            $table->integer('sort')->default(0)->comment('排序 同级有效');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_cities');
    }
}
