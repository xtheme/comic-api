<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('主键');
            $table->string('type', 20)->comment('域名类型：1、加密资源域名  2、微信推广域名 3、推广2层  4、主体域名 6、微信推广js域名 7、微信外链域名 8、资源域名  9、微信推广CDN域名  10、动态主体域名');
            $table->string('domain', 100)->comment('域名');
            $table->string('desc', 255)->nullable()->comment('使用说明');
            $table->tinyInteger('status')->comment('域名状态：1、备用；2、启用；3、被拦截');
            $table->tinyInteger('base64')->nullable()->default(0)->comment('資源域名用加密欄位0:不加密1加密');
            $table->timestamp('intercept_at')->nullable()->comment('域名拦截时间');
            $table->timestamp('expire_at')->nullable()->comment('到期時間');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
    }
}
