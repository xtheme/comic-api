<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('app_id')->default(0)->index('app_id')->comment('商戶ID');
            $table->integer('channel_id')->default(1)->index('channel_id')->comment('來源渠道ID');
            $table->string('name');
            $table->string('area', 5)->nullable()->comment('区码');
            $table->string('mobile', 11)->nullable()->comment('手机号');
            $table->string('email')->nullable()->unique('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('wallet')->nullable()->default(0)->comment('金币钱包');
            $table->dateTime('subscribed_until')->nullable()->comment('VIP到期时间');
            $table->tinyInteger('is_active')->nullable()->default(1)->comment('1=正常, 0=封禁');
            $table->tinyInteger('is_ban')->nullable()->default(0)->comment('0=正常, 1=黑單');
            $table->string('fingerprint')->comment('设备指纹');
            $table->timestamp('logged_at')->nullable()->comment('最近登入时间');
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
        Schema::dropIfExists('users');
    }
}
