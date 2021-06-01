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
            $table->increments('id')->comment('用户id');
            $table->string('username', 50)->comment('用户名');
            $table->integer('area')->default(86)->comment('區碼');
            $table->string('mobile', 11)->default('')->index('mobile')->comment('手机号码');
            $table->string('avatar')->default('')->comment('app用户头像');
            $table->unsignedInteger('score')->default(0)->comment('积分/金币');
            $table->text('sign')->nullable()->comment('个性签名');
            $table->string('signup_ip', 25)->nullable()->default('')->comment('注册ip');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 25)->nullable()->default('')->comment('登录ip');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态：0禁用，1启用');
            $table->integer('sex')->nullable()->comment('性别，男1女2未知0');
            $table->string('token', 800)->nullable()->default('')->index('token')->comment('app登录标识');
            $table->string('device_id', 100)->nullable()->unique('device_id')->comment('设备id（注册游客时）');
            $table->boolean('platform')->nullable()->default(0)->comment('设备平台  1安卓 2为ios');
            $table->string('version', 50)->default('')->comment('版本号');
            $table->unsignedInteger('del_comment')->nullable()->default(0)->comment('被删除评论数量');
            $table->unsignedInteger('total_comment')->nullable()->default(0)->comment('总评论');
            $table->timestamp('subscribed_at')->nullable();
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
