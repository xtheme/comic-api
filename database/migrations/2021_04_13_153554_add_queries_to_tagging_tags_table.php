<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQueriesToTaggingTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('tagging_tags', 'queries')) {
            Schema::table('tagging_tags', function (Blueprint $table) {
                $table->integer('queries')->unsigned()->default(0)->comment('被查詢次數');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagging_tags', function (Blueprint $table) {
            $table->dropColumn('queries');
        });
    }
}
