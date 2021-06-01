<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_new', function (Blueprint $table) {
            $table->increments('id')->comment('用户ID');
            $table->string('username', 50)->comment('用户名');
            $table->string('device_id', 100)->nullable()->unique('device_id')->comment('設備UUID（設備帳號）');
            $table->integer('area')->default(86)->comment('區碼');
            $table->string('mobile', 11)->default('')->index('mobile')->comment('手机号码');
            $table->string('avatar')->default('')->comment('用户头像');
            $table->integer('sex')->nullable()->comment('性别: 1=男, 2=女, 0=未知');
            $table->unsignedInteger('score')->default(0)->comment('积分');
            $table->text('sign')->nullable()->comment('个性签名');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态: 0=禁用, 1=启用');
            $table->string('token', 800)->nullable()->default('')->index('token')->comment('JWT');
            $table->boolean('platform')->nullable()->default(0)->comment('APP平台: 1=安卓, 2=ios');
            $table->string('version', 50)->default('')->comment('APP版本号');
            $table->dateTime('subscribed_at')->nullable()->comment('訂閱到期時間');
            $table->integer('sign_days')->default(0)->comment('签到天数');
            $table->string('register_ip', 25)->nullable()->default('')->comment('注册IP');
            $table->string('last_login_ip', 25)->nullable()->default('')->comment('登录IP');
            $table->timestamp('last_login_at')->nullable()->comment('最后一次登录时间');
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
        Schema::dropIfExists('users_new');
    }
}
