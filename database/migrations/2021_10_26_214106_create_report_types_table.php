<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_types', function (Blueprint $table) {
            $table->increments('id')->comment('漫画书举报类型 Id');
            $table->string('name', 120)->default('')->unique('tp_book_report_types_name_uindex')->comment('漫画书举报类型名称');
            $table->string('operator', 80)->nullable()->default('')->comment('操作人');
            $table->integer('sort')->nullable()->default(0)->comment('排序值  数值越大 排位越靠前');
            $table->boolean('status')->nullable()->default(0)->comment('使用状态   默认为0  使用中  1为已下架');
            $table->timestamps();
            $table->unsignedInteger('operator_id')->default(1)->comment('操作人id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_types');
    }
}
