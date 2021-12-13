<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->index('user_id')->comment('用戶ID');
            $table->string('fingerprint')->nullable()->comment('设备指纹');
            $table->ipAddress('ip')->index('ip');
            $table->string('host');
            $table->string('path')->index('path');
            $table->json('params');
            $table->unsignedDecimal('times', 8, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_request_logs');
    }
}
