<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('video_domains')) {
            Schema::create('video_domains', function (Blueprint $table) {
                $table->id();
                $table->string('title')->comment('CDN 名称');
                $table->string('domain')->comment('CDN 域名');
                $table->string('encrypt_domain')->comment('CDN 加密域名');
                $table->text('remark')->nullable()->comment('备注');
                $table->tinyInteger('sort')->unsigned()->default(0)->comment('排序');
                $table->boolean('status')->default(0)->comment('状态');
                $table->timestamps();
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
        Schema::dropIfExists('video_domains');
    }
}
