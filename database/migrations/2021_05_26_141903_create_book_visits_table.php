<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('book_visits')) {
            Schema::create('book_visits', function (Blueprint $table) {
                $table->id();
                $table->integer('book_id');
                $table->integer('chapter_id');
                $table->integer('user_id');
                $table->timestamps();

                $table->index('book_id');
                $table->index('chapter_id');
                $table->index('user_id');
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
        Schema::dropIfExists('book_visits');
    }
}
