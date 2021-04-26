<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresetToPricingPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_packages', function (Blueprint $table) {
            $table->tinyInteger('preset')->default(0)->comment('是否为预设套餐 [0=非预设  1=预设]')->after('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_packages', function (Blueprint $table) {
            $table->dropColumn('preset');
        });
    }
}
