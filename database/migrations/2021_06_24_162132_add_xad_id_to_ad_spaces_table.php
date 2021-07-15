<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddXadIdToAdSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('ad_spaces', 'xad_ios_id')) {
            Schema::table('ad_spaces', function (Blueprint $table) {
                $table->integer('xad_ios_id')->nullable()->after('class');
            });
        }

        if (!Schema::hasColumn('ad_spaces', 'xad_android_id')) {
            Schema::table('ad_spaces', function (Blueprint $table) {
                $table->integer('xad_android_id')->nullable()->after('xad_ios_id');
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
            $table->dropColumn('xad_ios_id');
            $table->dropColumn('xad_android_id');
        });
    }
}
