<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('book_rankings')) {
            Schema::create('book_rankings', function (Blueprint $table) {
                $table->id();
                $table->integer('book_id');
                $table->integer('views');
                $table->year('year');
                $table->tinyInteger('month');

                $table->index('book_id');
                $table->index(['year', 'month'], 'date');
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
        Schema::dropIfExists('book_rankings');
    }
}
