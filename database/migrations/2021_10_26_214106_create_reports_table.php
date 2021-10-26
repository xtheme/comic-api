<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id')->comment('漫画书举报记录 Id');
            $table->unsignedInteger('book_report_type_id')->default(0)->index('tp_book_reports_tp_book_report_types_id_fk')->comment('漫画书举报类型 Id');
            $table->unsignedInteger('user_id')->default(0)->index('tp_book_reports_ien_admin_user_id_index')->comment('用户 Id');
            $table->unsignedInteger('book_id')->nullable()->default(0)->index('tp_book_reports_tp_book_id_index')->comment('漫画书 Id');
            $table->timestamp('created_at')->useCurrent()->comment('记录添加时间戳');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
