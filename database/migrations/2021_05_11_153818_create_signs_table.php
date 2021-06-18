<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('signs')) {
            Schema::create('signs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->index()->comment('用户id');
                $table->timestamp('created_at')->useCurrent()->index()->comment('创建时间');
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
        Schema::dropIfExists('signs');
    }
}
