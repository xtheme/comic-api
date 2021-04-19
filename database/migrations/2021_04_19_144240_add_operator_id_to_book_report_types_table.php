<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperatorIdToBookReportTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_report_types', function (Blueprint $table) {
            $table->integer('operator_id')->unsigned()->default(1)->comment('操作人id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_report_types', function (Blueprint $table) {
            $table->dropColumn('operator_id');
        });
    }
}
