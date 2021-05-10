<?php

use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewsHistoriesGuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        $query = DB::table('histories')->select([
            'minor_id',
            'class',
            'type',
        ])->selectRaw('COUNT(*) as count')->where('user_vip', -1)->groupBy([
            'minor_id',
            'class',
            'type',
        ]);
        Schema::createOrReplaceView('views_histories_guest', $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropViewIfExists('views_histories_guest');
    }
}
