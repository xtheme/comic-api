<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayToAdSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ad_spaces', 'display')) {
            Schema::table('ad_spaces', function (Blueprint $table) {
                $table->integer('display')->default(1)->after('class')->comment('廣告位顯示方式 : [1:單圖 2:輪播 3:跑馬燈]');
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
        Schema::table('ad_spaces', function (Blueprint $table) {
            $table->dropColumn('display');
        });
    }
}
